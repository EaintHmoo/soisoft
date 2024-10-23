<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;


class QuotationListResource extends JsonResource
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
            'department_id' => $this->department_id,
            'department_name'    => $this->department?->name,
            'project_id'    => $this->project_id,
            'project_name'    => $this->project?->name,
            'type_of_sourcing'  => '',
            'evaluation_type'   => $this->evaluation_type,
            'tender_title'  => $this->quotation_title,
            'categories'    => $this->categories,
            'start_datetime'    => $this->start_datetime,
            'end_datetime'  => $this->end_datetime,
            'currency'  => $this->currency,
            'mode_of_submission'    => $this->mode_of_submission,
            'nda_required'  => $this->nda_required,
            'briefing_information_required' => $this->briefing_information_required,
            'briefing_date' => $this->briefing_date,
            'briefing_venue'    => $this->briefing_venue,
            'briefing_details'  => $this->briefing_details,
            'tender_state'  => $this->quotation_state,
            'tender_status'  => $this->quotation_status,
            'opportunity_type' => config('soisoft.opportunity_type.quotation'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
