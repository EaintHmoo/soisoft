<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;


class TenderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        $publication_check_list = $this->publication_check_list;
        return [
            'id' => $this->id,
            'tender_no' => $this->tender_no,
            'department_id' => $this->department_id,
            'department_name'    => $this->department?->name,
            'project_id'    => $this->project_id,
            'project_name'    => $this->project?->name,
            'type_of_sourcing'  => $this->type_of_sourcing,
            'evaluation_type'   => $this->evaluation_type,
            'tender_title'  => $this->tender_title,
            'category_id'    => $this->category_id,
            'category_name'    => $this->category?->name,
            'sub_category_id'    => $this->sub_category_id,
            'sub_category_name'    => $this->subCategory?->name,
            'start_datetime'    => $this->start_datetime,
            'end_datetime'  => $this->end_datetime,
            'currency'  => $this->currency,
            'mode_of_submission'    => $this->mode_of_submission,
            'nda_required'  => $this->nda_required,
            'nda_document'  => $this->nda_document ? URL::to('/storage/' . $this->nda_document) : null,
            'internal_details'  => $this->internal_details,
            'external_details'  => $this->external_details,
            'briefing_information_required' => $this->briefing_information_required,
            'briefing_date' => $this->briefing_date,
            'briefing_venue'    => $this->briefing_venue,
            'briefing_details'  => $this->briefing_details,
            'briefing_documents'    => array_map(function ($doc) {
                return $doc ? URL::to('/storage/' . $doc) : null;
            }, $this->briefing_documents ?? []),
            'fees_required' => $this->fees_required,
            'tender_fees'   => $this->tender_fees,
            'tender_fees_information'   => $this->tender_fees_information,
            'fees_documents'    => $this->fees_documents,
            'awarding_agency'   => $this->awarding_agency,
            'publication_check_list'    => array_filter(
                config('soisoft.publication_check_list'),
                function ($key) use ($publication_check_list) {
                    return in_array($key, $publication_check_list);
                },
                ARRAY_FILTER_USE_KEY
            ),
            'tender_state'  => $this->tender_state,
            'tender_status'  => $this->tender_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tender_items' => TenderDetailResource::collection($this->tenderItems),
            'tender_contacts' => $this->contacts,
            'documents' => DocumentResource::collection($this->documents),
            'suppliers' => $this->bidders->map(function ($value) {
                return $value?->name;
            }),
        ];
    }
}
