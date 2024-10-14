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
        return $this->record->department->name;
    }
}
