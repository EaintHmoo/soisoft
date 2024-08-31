<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use App\Filament\Buyer\Resources\TenderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTenders extends ListRecords
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Tender')
                ->icon('heroicon-m-document-plus'),
        ];
    }
}
