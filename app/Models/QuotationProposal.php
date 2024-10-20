<?php

namespace App\Models;

use App\Models\Buyer\Quotation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationProposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'quotation_proposals';

    protected $fillable =
    [
        'quotation_id',
        'bidder_id',
        'quotation_fee_receipt',
        'proposal_comment',
        'checklist_before_submit',
        'status',
        'cancel_reason',
        'cancel_comment',
    ];

    protected $casts = [
        'checklist_before_submit' => 'array',
    ];

    public function quotation() {
        return $this->belongsTo(Quotation::class, 'quotation_id');
    }
    
    public function bidder() {
        return $this->belongsTo(User::class, 'bidder_id');
    }
}
