<?php

namespace App\Models\Buyer;

use App\Models\User;
use App\Models\Admin\Category;
use App\Models\QuotationProposal;
use App\Models\TenderProposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_type',
        'department_id',
        'project_id',
        'reference_no',
        'quotation_title',
        'start_datetime',
        'end_datetime',
        'evaluation_type',
        'is_open_sourcing',
        'mode_of_submission',
        'currency',
        'nda_required',
        'nda_document',
        'delivery_contact_person',
        'delivery_address',
        'is_partial_delivery',
        'briefing_information_required',
        'briefing_date',
        'briefing_venue',
        'briefing_details',
        'briefing_documents',
        'publication_check_list',
        'quotation_state',
        'quotation_status',
    ];

    protected $casts = [
        'publication_check_list' => 'array',
        'briefing_documents' => 'array',
        'nda_required' => 'boolean',
        'briefing_information_required' => 'boolean',
        'is_partial_delivery' => 'boolean',
        'is_open_sourcing' => 'boolean'
    ];

    public function quotationContacts(): HasMany
    {
        return $this->hasMany(QuotationContact::class, 'quotation_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(QuotationDocument::class, 'quotation_id');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'quotation_contact');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function quotationItems(): HasMany
    {
        return $this->hasMany(QuotationItem::class, 'quotation_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'quotation_category', 'quotation_id', 'category_id');
    }

    public function bidders(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'closed_quotation_bidders', 'quotation_id', 'supplier_id');
    }

    public function quotationProposals(): HasMany
    {
        return $this->hasMany(TenderProposal::class, 'tender_id', 'id');
    }

    public function quotation_proposals(): HasMany
    {
        return $this->hasMany(QuotationProposal::class, 'quotation_id', 'id');
    }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'quotation_proposals', 'quotation_id', 'bidder_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (Tender $record) {
            $record->contacts()->detach();
        });
    }
}
