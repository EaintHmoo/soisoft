<?php

namespace App\Filament\Buyer\Resources\ContactResource\Pages;

use App\Filament\Buyer\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageContacts extends ManageRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver(),
        ];
    }
}
