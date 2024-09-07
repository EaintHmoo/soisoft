<?php

namespace App\Models\Buyer;

use App\Models\Admin\Category;
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
        'quotation_state'
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

    public function quotationDocuments(): HasMany
    {
        return $this->hasMany(QuotationDocument::class, 'quotation_id');
    }

    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Contact::class, 'quotation_contact');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'quotation_document');
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

    protected static function booted(): void
    {
        static::deleting(function (Tender $record) {
            $record->contacts()->detach();
            $record->documents()->detach();
        });
    }
}