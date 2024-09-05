<?php

namespace App\Models\Buyer;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationContact extends Model
{
    use HasFactory;

    protected $table = 'quotation_contact';

    protected $fillable = [
        'quotation_id',
        'contact_id'
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }
 
    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }
}
