<?php

namespace App\Http\Controllers\API\v1;


use App\Traits\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\file_upload;
use App\Models\folder_file_relation;
use App\Models\Patient;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class fileuploadController extends Controller
{
    use FileUpload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $patientClusters = file_upload::select('cluster', 'folder_path')

                ->groupBy('cluster', 'folder_path')
                ->get();
            if ($patientClusters->isEmpty()) {
                return response()->json(['error' => 'No files found for the patient'], 404);
            }

            $sizesByClusters = []; // Separate array for sizes

            foreach ($patientClusters as $file) {
                $cluster = $file->cluster;
                $url = Storage::disk('public')->url($file->folder_path);
                // Append the URL to the cluster array
                $urlsByClusters[$cluster][] = $url;
            }
            return response()->json(['urlsByClusters' => $urlsByClusters]);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'An error occurred while retrieving the file URLs'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $user = auth()->id();
            if ($request->hasFile('files')) {

                $patient  = Patient::findorfail($request->patient_id);
                $patientFolder = $patient->p_folder;

                $uploadedFiles = $request->file('files');
                $cluster = 'cluster' . Str::random(10);

                foreach ($uploadedFiles as $uploadedFile) {

                    $originalFilename = $uploadedFile->getClientOriginalName();
                    $path = $this->UploadFile($uploadedFile, $patientFolder, '', 'public');

                    file_upload::create([
                        'doctor_id' => $user,
                        'patient_id' => $request->patient_id,
                        'original_name' => $originalFilename,
                        'folder_path' => $path,
                        'type' => $request->type,
                        'cluster' => $cluster,
                    ]);
                }
                return response()->json([
                    'message' => 'upload created successfully',
                ], 201);
            }
        } catch (\Throwable $th) {
            // Log the exception for debugging
            Log::error($th);

            return response()->json([
                'message' => 'An error occurred during file upload.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        try {

            $patientClusters = file_upload::select('cluster', 'folder_path')
                ->where('cluster', $id)
                ->groupBy('cluster', 'folder_path')
                ->get();
            if ($patientClusters->isEmpty()) {
                return response()->json(['error' => 'huh'], 404);
            }

            $data = [];


            foreach ($patientClusters as $file) {

                $url = asset("storage/" . $file->folder_path);

                $data[] = $url;
            }


            return view('welcome', compact('data'));
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'An error occurred while retrieving the file URLs'], 500);
        }
    }
    public function uploadsInfo(Request $request)
    {

        try {

            $patientClusters = file_upload::select('cluster', 'folder_path', 'created_at', 'patient_id', 'type', 'original_name')
                ->groupBy('cluster', 'folder_path', 'created_at', 'patient_id', 'type', 'original_name')
                ->get();

            if ($patientClusters->isEmpty()) {
                return response()->json(['error' => 'No files found for the patient', 'data' => []]);
            }

            $datesByClusters = [];
            $sizesByClusters = [];
            $links = [];
            $clusterType = [];
            $clusterMime = [];
            $patients = [];
            foreach ($patientClusters as $file) {
                $cluster = $file->cluster;
                $zip = new ZipArchive;
                $zipFileName = "download_{$cluster}.zip";
                $zipPath = storage_path("app/public/" . $zipFileName);
                if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                    $zip->addFile(storage_path("app/public/{$file->folder_path}"), basename($file->folder_path));
                    $zip->close();
                }
                $sizeInBytes = Storage::disk('public')->size($file->folder_path);
                $sizeInKB = $sizeInBytes / 1024 / 1024;
                $sizesByClusters[$cluster][] = $sizeInKB;
                $datesByClusters[$cluster][] = $file->created_at->toDateTimeString();
                $links[$cluster][] = Storage::disk('public')->url($file->folder_path);
                $links[$cluster][] = asset($zipFileName);
                $clusterMime[$cluster][] = Storage::disk('public')->mimeType($file->folder_path);
                $clusterType[$cluster][] = $file->type;
                if (!isset($patients[$cluster][$file->patient_id])) {
                    $patientInfo = Patient::find($file->patient_id);
                    $patients[$cluster][$file->patient_id] = [
                        'nom' => $patientInfo->nom . ' ' . $patientInfo->prenom,

                    ];
                }
            }
            // Calculate total size for each cluster
            $Uploadsinfo = [];
            foreach ($sizesByClusters as $cluster => $sizes) {
                $Uploadsinfo[$cluster] = [
                    'patientName' => array_values($patients[$cluster]),
                    'type' =>  $clusterType[$cluster][0],
                    'dates' => $datesByClusters[$cluster],
                    'totalSize' => array_sum($sizes),
                    'mimeType' =>  $clusterMime[$cluster],
                    'links' => url("storage/download_{$cluster}.zip")
                ];
            }
            $response = response()->json(['data' => $Uploadsinfo], 201);
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Authorization');
            return $response;
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(['error' => 'An error occurred while retrieving the file URLs'], 404);
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
