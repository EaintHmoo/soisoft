<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenderDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'name',
        'document_type',
        'document_path',
        'required_resubmit',
        'question_columns',
        'answer_columns',
        'comparable',
    ];

    public function tender(): BelongsTo 
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }
}
