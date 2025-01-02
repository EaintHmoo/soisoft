<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidQuotationItem extends Model
{
    use HasFactory;

    protected $table = 'bid_quotation_items';

    protected $fillable =
    [
        'quotation_item_id',
        'bidder_id',
        'quantity',
        'unit_price',
    ];
}
