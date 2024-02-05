<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentKpi extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'phone_number' => $this->patient->phone_number,
            'date' => $this->date,
            'patient_name' => $this->patient->nom . ' ' . $this->patient->prenom,
            'note' => $this->note,
        ];
    }
}
