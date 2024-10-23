<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class SupplierTenderListResource extends JsonResource
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
            'tender_no' => $this->reference_no,
            'department_name'    => $this->department?->name,
            'project_name'    => $this->project?->name,
            'tender_title'  => $this->tender_title,
            'tender_status'  => $this->tender_status,
            'opportunity_type' => config('soisoft.opportunity_type.tender'),
        ];
    }
}
