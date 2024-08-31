<?php

namespace App\Filament\Buyer\Resources\DepartmentResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Buyer\Resources\DepartmentResource;

class ManageDepartments extends ManageRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth(MaxWidth::Medium),
        ];
    }
}
