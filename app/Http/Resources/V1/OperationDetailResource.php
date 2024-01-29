<?php

namespace App\Http\Resources\V1;

use App\Models\Payement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'tooth_id' => $this->tooth_id,
            'operation_type' => $this->operation_type,
            'price' => $this->price,
        ];
    }
}
