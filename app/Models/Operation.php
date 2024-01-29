<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function payments()
    {
        return $this->hasMany(Payement::class, 'operation_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function operationdetails()
    {
        return $this->hasMany(OperationDetail::class, 'operation_id');
    }
}
