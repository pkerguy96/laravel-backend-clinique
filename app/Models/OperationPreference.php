<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationPreference extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function operationUserpref()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
