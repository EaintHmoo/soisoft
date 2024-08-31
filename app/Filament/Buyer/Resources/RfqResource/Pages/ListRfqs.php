<?php

namespace App\Filament\Buyer\Resources\RfqResource\Pages;

use App\Filament\Buyer\Resources\RfqResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRfqs extends ListRecords
{
    protected static string $resource = RfqResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
