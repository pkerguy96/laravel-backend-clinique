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
        $operationType = $this->preference ? $this->preference->name : 'Unknown'; // Provide a default value if preference is null


        return [
            'id' => $this->id,
            'tooth_id' => $this->tooth_id,
            'operation_type' => $operationType,
            'price' => $this->price,
        ];
    }
}
