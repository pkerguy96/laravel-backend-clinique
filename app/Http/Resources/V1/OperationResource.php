<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'operation_details' => OperationDetailResource::collection($this->operationdetails),
            'payments' => PayementResource::collection($this->payments),
            'total_cost' => $this->total_cost,
            'is_paid' => $this->is_paid,
            'note' => $this->note,

        ];
    }
}
