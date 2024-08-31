<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use App\Filament\Buyer\Resources\TenderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTender extends EditRecord
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
