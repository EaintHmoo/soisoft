<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'part_number',
        'uom',
        'estimate_quantity',
        'specifications',
        'description',
        'notes_to_supplier',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }
}
