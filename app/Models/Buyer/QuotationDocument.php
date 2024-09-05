<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationDocument extends Model
{
    use HasFactory;

    protected $table = 'quotation_document';

    protected $fillable = [
        'quotation_id',
        'document_id'
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
 
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
