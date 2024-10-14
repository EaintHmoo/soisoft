<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Filament\Buyer\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotation extends ViewRecord
{
    protected static string $resource = QuotationResource::class;

    protected static ?string $navigationIcon = '';

    public static function getNavigationLabel(): string
    {
        return 'Overview';
    }

    public function getTitle(): string
    {
        return 'View #' . $this->record->reference_no;
    }

    public function getSubheading(): ?string
    {
        return $this->record->department->name;
    }
}
