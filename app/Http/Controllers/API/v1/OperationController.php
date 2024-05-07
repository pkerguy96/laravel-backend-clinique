<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OperationCollection;
use App\Http\Resources\V1\OperationResource;
use App\Http\Resources\V1\PayementResource;
use App\Http\Resources\v1\treatementOperationCollection;
use App\Models\Operation;
use App\Models\OperationDetail;
use App\Models\Payement;
use App\Traits\HttpResponses;
use App\Traits\PermissionCheckTrait;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    use HttpResponses;
    use PermissionCheckTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $permissionResult = $this->checkPermission('access_debt');
        if ($permissionResult instanceof JsonResponse) {
            return $permissionResult;
        }
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operations = Operation::where('doctor_id', $doctorId)->with('payments', 'operationdetails')->orderBy('id', 'desc')->get();
        return new OperationCollection($operations);
    }
    //TODO : give it permission 
    public function recurringOperation()
    {
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operations = Operation::where('doctor_id', $doctorId)
            ->where('treatment_nbr', '>', 0)
            ->where('treatment_isdone', '!=', 1)
            ->with('operationdetails')
            ->orderBy('id', 'desc')
            ->get();

        return new treatementOperationCollection($operations);
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


        //TODO: refactor this
        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            $permissionResult = $this->checkPermission('insert_debt');
            if ($permissionResult instanceof JsonResponse) {
                return $permissionResult;
            }
            $data = $request->all();
            $validator = validator($request->all(), [
                'is_paid' => 'required|boolean',
                'note' => 'nullable|string',
                'patient_id' => 'required|integer|exists:patients,id',
                'amount_paid' => 'nullable|numeric',
                'operations' => 'required|array',

                'operations.*.price' => 'required|numeric|between:0,9999999.99',
                'operations.*.amount_paid' => 'nullable|numeric|between:0,9999999.99',
                'operations.*.tooth_id' => 'required|array',
            ], [
                'is_paid.required' => 'Le champ "is_paid" est requis.',
                'is_paid.boolean' => 'Le champ "is_paid" doit être un booléen.',

                'note.string' => 'Le champ "note" doit être une chaîne de caractères.',

                'patient_id.required' => 'Le champ "patient_id" est requis.',
                'patient_id.integer' => 'Le champ "patient_id" doit être un entier.',
                'patient_id.exists' => 'Le patient sélectionné n\'existe pas.',

                'amount_paid.numeric' => 'Le champ "amount_paid" doit être un nombre.',


                'operations.required' => 'Le champ "operations" est requis.',
                'operations.array' => 'Le champ "operations" doit être un tableau.',

                'operations.*.operation_type.required' => 'Le champ "operation_type" est requis.',


                'operations.*.price.required' => 'Le champ "price" est requis.',
                'operations.*.price.numeric' => 'Le champ "price" doit être un nombre.',
                'operations.*.price.between' => 'Le champ "price" doit être compris entre :min et :max.',

                'operations.*.amount_paid.numeric' => 'Le champ "amount_paid" doit être un nombre.',
                'operations.*.amount_paid.between' => 'Le champ "amount_paid" doit être compris entre :min et :max.',

                'operations.*.tooth_id.required' => 'Le champ "tooth_id" est requis.',
                'operations.*.tooth_id.array' => 'Le champ "tooth_id" doit être un tableau.',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }


            $data = $request->json()->all();
            $calculator = 0;
            foreach ($data['operations'] as $item) {
                $calculator += $item['price'];
            }
            DB::beginTransaction();
            $operation = Operation::create([
                'doctor_id' => $doctorId,
                'patient_id' => $data['patient_id'],
                'total_cost' => $calculator,
                'is_paid' => $data['is_paid'],
                'note' =>  $data['note'],
            ]);
            foreach ($data['operations'] as $item) {
                OperationDetail::create([
                    'operation_id' =>  $operation->id,
                    'tooth_id' => implode(',', $item['tooth_id']),
                    'operation_type' => $item['operation_type'],
                    'price' => $item['price'],
                ]);
            }

            Payement::create([
                'operation_id' =>  $operation->id,
                'total_cost' => $calculator,
                'amount_paid' => $data['is_paid'] ? $calculator : $data['amount_paid'],

            ]);

            DB::commit();

            return response()->json([
                'message' => 'operation created successfully',
                'operation_id' => $operation->id

            ], 201);
        } catch (\Exception $e) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'message' => 'Oops something went wrong',
                'errors' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function modifyoperation(string $id)
    {

        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            $operation =  Operation::where('doctor_id', $doctorId)->where('id', $id)->first();
            $operation->treatment_nbr++;
            $operation->save();
            return $this->success(null, 'yey', 201);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'oops', 501);
        }
    }
    public function treatmentisdone(string $id)
    {

        try {
            $user = Auth::user();
            $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
            $operation =  Operation::where('doctor_id', $doctorId)->where('id', $id)->first();
            if ($operation->treatment_isdone > 0) {
                return $this->error(null, 'treatment is already done', 500);
            }
            $operation->treatment_isdone++;
            $operation->save();
            return $this->success(null, 'yey', 201);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 'oops', 501);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $permissionResult = $this->checkPermission('insert_debt');
            if ($permissionResult instanceof JsonResponse) {
                return $permissionResult;
            }
            $operation = Operation::findorfail($id);

            if ($operation) {
                $sumAmountPaid = (float)Payement::where('operation_id', $id)->sum('amount_paid');
                $totalCost = (float)$operation->total_cost;
                $amountPaid = (float)$request->amount_paid;

                if (!isset($amountPaid) || empty($amountPaid)) {
                    return response()->json(['error' => 'Le montant payé est requis'], 400);
                }
                if ($amountPaid > $totalCost) {

                    // The amount paid exceeds the total cost
                    return response()->json(['error' => "Le montant payé dépasse le coût total."], 400);
                } elseif ($sumAmountPaid + $amountPaid > $totalCost) {

                    return response()->json(['error' => "Le montant total payé dépasse le coût total."], 400);
                } elseif ($sumAmountPaid + $amountPaid <= $totalCost) {

                    $payement =   Payement::create([
                        'operation_id' => $operation->id,
                        'total_cost' => $totalCost,
                        'amount_paid' => $amountPaid,
                    ]);
                    $operation->update(['is_paid' => $sumAmountPaid + $amountPaid === $totalCost ? 1 : 0]);
                    return response()->json([
                        'message' => "Paiement ajouté avec succès.",
                        'data' => new PayementResource($payement)
                    ]);
                }
            } else {
                return response()->json(['message' => "Aucun identifiant d'opération n'existe."]);
            }
        } catch (\Exception $e) {

            return response()->json(['message' => 'Error updating Ordonance'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        Operation::where('doctor_id', $doctorId)->findorfail($id)->delete();
        return response()->json(['message' => 'Operation deleted successfully'], 204);
    }
    public function getByOperationId($operationId)
    {
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operation = Operation::with(['operationdetails.preference', 'operationdetails'])->where('id', $operationId)->where('doctor_id', $doctorId)->first();

        logger($operation->operationdetails->pluck('preference'));
        // Transform the result using the resource
        return new OperationResource($operation);
    }
    public function deletePaymentDetail($id)
    {
        $user = Auth::user();
        $permissionResult = $this->checkPermission('delete_debt');
        if ($permissionResult instanceof JsonResponse) {
            return $permissionResult;
        }
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        // Retrieve operation ID for the payment
        $operationId = Payement::where('id', $id)->value('operation_id');
        // Delete the payment by getting the operation first 
        Payement::whereHas('operation', function ($query) use ($doctorId) {
            $query->where('doctor_id', $doctorId);
        })->findOrFail($id)->delete();
        // Calculate total paid amount and total price
        $sumAmountPaid = (float) Payement::where('operation_id', $operationId)->sum('amount_paid');
        $totalPrice = (float) Operation::where('id', $operationId)->where('doctor_id', $doctorId)->value('total_cost');
        // Update operation status based on payment status
        Operation::where('id', $operationId)->update(['is_paid' => ($sumAmountPaid === $totalPrice) ? 1 : 0]);
        return response()->json(['message' => 'Payment deleted successfully'], 204);
    }
    public function PayementVerificationCheckout($id)
    {
        $user = Auth::user();
        $doctorId = ($user->role === 'nurse') ? $user->doctor_id : $user->id;
        $operation = (int) Operation::where('doctor_id', $doctorId)->where('id', $id)->pluck('is_paid')->first();

        return response()->json(['data' => $operation]);
    }
}
