<?php

namespace App\Filament\Admin\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Support\Colors\Color;
use App\Filament\Imports\CategoryImporter;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Admin\Resources\CategoryResource;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver(),
            // ImportAction::make()
            //     ->label('Import Categories')
            //     ->color(Color::Orange)
            //     ->importer(CategoryImporter::class)
        ];
    }
}
