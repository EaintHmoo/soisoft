<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Filament\Buyer\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQuotation extends ViewRecord
{
    protected static string $resource = QuotationResource::class;

    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['quotation_state'] == 'draft') {
            return false;
        }
        return true;
    }

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
        return $this->record->department != null ? $this->record->department->name : '';
    }
}
