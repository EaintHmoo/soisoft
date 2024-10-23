<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'tender_id' => $this->tender_id,
            'part_number' => $this->part_number,
            'uom' => $this->uom,
            'estimate_quantity' => $this->estimate_quantity,
            'specifications' => $this->specifications,
            'description' => $this->description,
            'notes_to_supplier' => $this->notes_to_supplier,
            'opportunity_type' => config('soisoft.opportunity_type.tender'),
        ];
    }
}
