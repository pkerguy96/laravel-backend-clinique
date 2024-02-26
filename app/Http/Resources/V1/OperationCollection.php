<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\PayementResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V1\PatientResource;
use Illuminate\Support\Carbon;

class OperationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed> 
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($operation) {
            $totalAmountPaid = $operation->payments->sum('amount_paid');

            return [
                'isPaid' => $operation->is_paid,
                'id' => $operation->id,
                'patient_id' => $operation->patient_id,
                'nom' => $operation->patient->nom,
                'prenom' => $operation->patient->prenom,
                'operation_details' => OperationDetailResource::collection($operation->operationdetails),
                'date' => Carbon::parse($operation->created_at)->toDateString(),
                'payments' => PayementResource::collection($operation->payments),
                'totalPaid' => $totalAmountPaid,
                'total_cost' => $operation->total_cost

            ];
        });
    }
}
