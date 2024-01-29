<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'title',
        'date',
        'note',
    ];
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
