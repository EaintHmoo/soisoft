<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;


class QuotationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $publication_check_list = $this->publication_check_list;
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
            'nda_document'  => $this->nda_document ? URL::to('/storage/' . $this->nda_document) : null,
            'briefing_information_required' => $this->briefing_information_required,
            'briefing_date' => $this->briefing_date,
            'briefing_venue'    => $this->briefing_venue,
            'briefing_details'  => $this->briefing_details,
            'briefing_documents'    => array_map(function ($doc) {
                return $doc ? URL::to('/storage/' . $doc) : null;
            }, $this->briefing_documents ?? []),
            'tender_state'  => $this->quotation_state,
            'tender_status'  => $this->quotation_status,
            'created_at' => $this->created_at,
            'tender_proposal_id' => $this->quotationProposal ? $this->quotationProposal?->id : null,
            'tender_proposal_status' => $this->quotationProposal ? $this->quotationProposal?->status : config('soisoft.tender_proposal_status.pending'),
            'publication_check_list'    => array_filter(
                config('soisoft.publication_check_list'),
                function ($key) use ($publication_check_list) {
                    return in_array($key, $publication_check_list);
                },
                ARRAY_FILTER_USE_KEY
            ),
            'tender_items' => QuotationDetailResource::collection($this->quotationItems),
            'tender_contacts' => QuotationContactResource::collection($this->contacts),
            'documents' => DocumentResource::collection($this->documents),
            'suppliers' => $this->suppliers->map(function ($value) {
                return $value?->name;
            }),
            'is_nda_accepted' => $this->quotationNdaAccept?->is_accept ?? 0,
            'opportunity_type' => config('soisoft.opportunity_type.quotation'),
        ];
    }
}
