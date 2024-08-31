<?php

namespace App\Filament\Buyer\Resources\DocumentResource\Pages;

use App\Filament\Buyer\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDocuments extends ManageRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->slideOver(),
        ];
    }
}
