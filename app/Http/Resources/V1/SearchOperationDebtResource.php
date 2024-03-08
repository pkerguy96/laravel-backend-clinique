<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchOperationDebtResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'patient_name' => $this->patient->nom,
            'patient_prenom' => $this->patient->prenom,
            'operation_created_at' => $this->created_at,
            'total_cost' => $this->total_cost,
            'operation_type' => OperationDetailResource::collection($this->operationdetails),
            'total_amount_paid' => $this->payments->sum('amount_paid'),
        ];
    }
}
