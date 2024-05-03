<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class treatementOperationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($operation) {
            return [
                'id' => $operation->id,
                'name' => $operation->patient->nom . ' ' . $operation->patient->prenom,
                'date' => $operation->created_at->toDateString(),
                'cost' => $operation->total_cost,
                'treatment_nbr' => $operation->treatment_nbr,
                'operation_code' => $operation->operationdetails->pluck('operation_type')->implode(', '),
                'teeth' => $operation->operationdetails->pluck('tooth_id')

            ];
        });
    }
}
