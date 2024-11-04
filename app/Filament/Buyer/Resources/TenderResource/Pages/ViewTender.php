<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Buyer\Resources\TenderResource;

class ViewTender extends ViewRecord
{
    protected static string $resource = TenderResource::class;

    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['tender_state'] == 'draft') {
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
        return 'View #' . $this->record->tender_no;
    }

    public function getSubheading(): ?string
    {
        return $this->record->department != null ? $this->record->department->name : '';
    }
}
