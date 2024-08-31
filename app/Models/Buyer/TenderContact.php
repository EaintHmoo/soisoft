<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class TenderContact extends Pivot
{
    use HasFactory;

    protected $table = 'tender_contact';

    protected $fillable = [
        'tender_id',
        'contact_id'
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
 
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
