<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationDetailResource extends JsonResource
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
            'tender_id' => $this->quotation_id,
            'type' => $this->type,
            'quantity' => $this->quantity,
            'uom' => $this->uom,
            'category_id' => $this->category_id,
            'expected_delivery_date' => $this->expected_delivery_date,
            'delivery_terms' => $this->delivery_terms,
            'payment_terms' => $this->payment_terms,
            'payment_mode' => $this->payment_mode,
            'description' => $this->description,
            'remark' => $this->remark,
            'company_estimated_unit_price' => $this->company_estimated_unit_price,
            'historical_unit_price' => $this->historical_unit_price,
            'same_as_header_address' => $this->same_as_header_address,
            'delivery_contact_person' => $this->delivery_contact_person,
            'delivery_address' => $this->delivery_address,
        ];
    }
}
