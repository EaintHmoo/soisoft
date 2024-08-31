<?php

namespace App\Filament\Admin\Resources\TenderCategoryResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Support\Colors\Color;
use App\Filament\Imports\CategoryImporter;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Admin\Resources\TenderCategoryResource;

class ManageTenderCategories extends ManageRecords
{
    protected static string $resource = TenderCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver(),
            ImportAction::make()
                ->label('Import Categories')
                ->color(Color::Orange)
                ->importer(CategoryImporter::class)
        ];
    }
}
