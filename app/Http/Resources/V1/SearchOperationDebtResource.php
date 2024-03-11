<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;

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
            'name' => $this->patient->nom . ' ' . $this->patient->prenom,
            'date' => Carbon::parse($this->created_at)->toDateString(),
            'total_cost' => (float) $this->total_cost,
            'operation_type' => $this->MapOperationdetails($this->operationdetails),
            'total_amount_paid' => (float) $this->payments->sum('amount_paid'),
            'amount_due' => (float) $this->total_cost - (float) $this->payments->sum('amount_paid'),


        ];
    }

    public function mapOperationDetails($details)
    {
        $operationTypes = $details->pluck('operation_type')->implode(', ');
        return explode(', ', $operationTypes);
    }
}
