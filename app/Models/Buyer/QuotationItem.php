<?php

namespace App\Models\Buyer;

use App\Models\Buyer\Quotation;
use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'type',
        'quantity',
        'uom',
        'category_id',
        'expected_delivery_date',
        'delivery_terms',
        'payment_terms',
        'payment_mode',
        'description',
        'remark',
        'company_estimated_unit_price',
        'historical_unit_price',
        'same_as_header_address',
        'delivery_contact_person',
        'delivery_address',
    ];

    protected $casts = [
        'same_as_header_address' => 'boolean'
    ];

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
