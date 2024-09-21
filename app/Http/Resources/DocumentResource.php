<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;


class DocumentResource extends JsonResource
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
            'id'    => $this->id,
            'name'  => $this->name,
            'document_type' => $this->document_type,
            'document_path' => $this->document_path ? URL::to('/storage/' . $this->document_path) : null,
            'required_resubmit' => $this->required_resubmit,
            'question_columns'  => $this->question_columns,
            'answer_columns'    => $this->answer_columns,
            'comparable'    => $this->comparable,
            'document_by'   => $this->document_by?->name,
            'comment'       => $this->comment,
        ];
    }
}
