<?php

namespace App\Models\Buyer;

use App\Models\Admin\TenderCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'buyer_id',
        'tender_no',
        'department_id',
        'project_id',
        // 'tender_type',
        'type_of_sourcing',
        'evaluation_type',
        'tender_title',
        'tender_category_id',
        'tender_sub_category_id',
        'start_datetime',
        'end_datetime',
        'currency',
        'mode_of_submission',
        'nda_required',
        'nda_document',
        'internal_details',
        'external_details',
        'briefing_information_required',
        'briefing_date',
        'briefing_venue',
        'briefing_details',
        'briefing_documents',
        'fees_required',
        'tender_fees',
        'tender_fees_information',
        'fees_documents',
        'awarding_agency',
        'publication_check_list',
        'tender_state',
        'tender_status',
    ];

    protected $casts = [
        'publication_check_list' => 'array',
        'briefing_documents' => 'array',
        'nda_required' => 'boolean',
        'fees_required' => 'boolean',
        'briefing_information_required' => 'boolean'
    ];

    public function category(): BelongsTo 
    {
        return $this->belongsTo(TenderCategory::class, 'tender_category_id');
    }

    public function subCategory(): BelongsTo 
    {
        return $this->belongsTo(TenderCategory::class, 'tender_sub_category_id');
    }

    public function tenderContacts(): HasMany
    {
        return $this->hasMany(TenderContact::class, 'tender_id');
    }

    public function tenderDocuments(): HasMany
    {
        return $this->hasMany(TenderDocument::class, 'tender_id');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'tender_contact');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'tender_document');
    }

    public function tenderItems(): HasMany
    {
        return $this->hasMany(TenderItem::class, 'tender_id');
    }

    public function department(): BelongsTo 
    {
        return $this->belongsTo(Department::class);
    }

    public function project(): BelongsTo 
    {
        return $this->belongsTo(Project::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Tender $record) {
            $record->contacts()->detach();
            $record->documents()->detach();
        });
    }
}
