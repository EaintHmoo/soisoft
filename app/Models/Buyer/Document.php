<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document_type',
        'document_path',
        'required_resubmit',
        'question_columns',
        'answer_columns',
        'comparable',
    ];

    public function tenders(): BelongsToMany 
    {
        return $this->belongsToMany(Tender::class, 'tender_tender_document');
    }

    protected static function booted(): void
    {
        static::deleting(function (Document $record) {
            $record->tenders()->detach();
        });
    }
}
