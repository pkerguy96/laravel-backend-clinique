<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
}
