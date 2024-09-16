<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'name',
        'document_type',
        'document_path',
        'description',
    ];

    public function quotation(): BelongsTo 
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
}
