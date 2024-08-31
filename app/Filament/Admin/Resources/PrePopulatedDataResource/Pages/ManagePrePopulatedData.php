<?php

namespace App\Filament\Admin\Resources\PrePopulatedDataResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Admin\Resources\PrePopulatedDataResource;

class ManagePrePopulatedData extends ManageRecords
{
    protected static string $resource = PrePopulatedDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth(MaxWidth::TwoExtraLarge),
        ];
    }
}
