<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OperationDetail;

class PayementResource extends JsonResource
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
            'total_cost' => $this->total_cost,
            'amount_paid' => $this->amount_paid,
            'date' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
