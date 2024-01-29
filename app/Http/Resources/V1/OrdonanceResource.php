<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdonanceResource extends JsonResource
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
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'date' => $this->date,
            'ordonance_details' => OrdonanceDetailsResource::collection($this->ordonanceDetails),

        ];
    }
}
