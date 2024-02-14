<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'nom',
        'prenom',
        'cin',
        'date',
        'address',
        'sex',
        'phone_number',
        'mutuelle',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($patient) {
            // Use the patient's ID to create a folder
            $patientFolder = 'patients/' . $patient->id;
            // Create the folder using the Storage facade
            Storage::disk('public')->makeDirectory($patientFolder);
            // Assign the folder to the patient
            $patient->p_folder = $patientFolder;
            $patient->save(); // Save the patient to persist the changes
        });
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    public function Ordonance()
    {
        return $this->hasMany(Ordonance::class, 'patient_id');
    }
    public function operations()
    {
        return $this->hasMany(Operation::class, 'patient_id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id ');
    }
    public function files()
    {
        return $this->hasMany(file_upload::class, 'patient_id');
    }
}
