<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenderDocument extends Pivot
{
    use HasFactory;

    protected $table = 'tender_document';

    protected $fillable = [
        'tender_id',
        'document_id'
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
 
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
