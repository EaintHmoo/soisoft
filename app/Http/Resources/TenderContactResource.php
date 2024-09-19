<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenderContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>  $this->id,
            "contact_person" => $this->contact_person,
            "designation" => $this->designation,
            "phone" => $this->phone,
            "email" => $this->email,
            "address" => $this->address,
        ];
    }
}
