<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationDetail extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function Operation()
    {
        return $this->belongsTo(Operation::class, 'operation_id');
    }
    public function preference()
    {
        return $this->belongsTo(OperationPreference::class, 'operation_type', 'code');
    }
}
