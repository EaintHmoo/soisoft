<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationQuestionResource extends JsonResource
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
            'quotation_id' => $this->quotation_id,
            'question_by_id' => $this->question_by_id,
            'question_by' => $this->question_by?->name,
            'answer_by_id' => $this->answer_by_id,
            'answer_by' => $this->answer_by?->name,
            'question'  => $this->question,
            'answer'    => $this->answer,
            'asked_on'    => $this->created_at,
        ];
    }
}
