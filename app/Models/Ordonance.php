<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordonance extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'date',
    ];
    public function OrdonanceDetails()
    {
        return $this->hasMany(OrdonanceDetails::class, 'ordonance_id');
    }
    public function Doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function Patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
