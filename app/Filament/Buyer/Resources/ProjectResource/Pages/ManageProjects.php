<?php

namespace App\Filament\Buyer\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\MaxWidth;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Buyer\Resources\ProjectResource;

class ManageProjects extends ManageRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth(MaxWidth::Medium),
        ];
    }
}
