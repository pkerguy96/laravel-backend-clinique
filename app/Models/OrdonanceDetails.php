<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdonanceDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'ordonance_id',
        'medicine_name',
        'note',
    ];
    public function Ordonance()
    {
        return $this->belongsTo(Ordonance::class, 'ordonance_id');
    }
}
