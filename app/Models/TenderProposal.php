<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenderProposal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tender_proposals';

    protected $fillable =
    [
        'tender_id',
        'bidder_id',
        'tender_fee_receipt',
        'proposal_comment',
        'checklist_before_submit',
        'status',
        'cancel_reason',
        'cancel_comment',
    ];

    protected $casts = [
        'checklist_before_submit' => 'array',
    ];
}
