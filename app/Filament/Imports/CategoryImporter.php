<?php

namespace App\Filament\Imports;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Admin\TenderCategory;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Models\Import;

class CategoryImporter extends Importer
{
    protected static ?string $model = TenderCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->helperText('Select parent column to import the parent categories or select child column to import the child categories.'),

            ImportColumn::make('key')
                ->castStateUsing(function (string $state) {
                    if (blank($state)) {
                        return null;
                    }
                    return $state;
                })
                ->rules(fn ($record) => [Rule::unique('tender_categories')->ignore($record->id)])
                ->helperText('You don\'t have to select this coulumn if you are importing the child categories.'),

            ImportColumn::make('slug')
                ->fillRecordUsing(function (TenderCategory $record, string $state): void {
                    $record->slug = Str::slug($state);
                })
                ->helperText('Select parent column to import the parent categories or select child column to import the child categories.'),

            ImportColumn::make('parent')
                ->relationship(resolveUsing: function (string $state): ?TenderCategory {
                    return TenderCategory::where('key', $state)
                        ->first();
                })
                ->castStateUsing(function (string $state) {
                    if (blank($state)) {
                        return null;
                    }
                    return $state;
                })
                ->helperText('Select parent key if you are importing the child categories. Otherwise no need to select.'),
        ];
    }

    public function resolveRecord(): ?TenderCategory
    {
        if(!isset($this->data['name']) || is_null($this->data['name'])) return null;
        
        return TenderCategory::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'key' => isset($this->data['key']) ?? null,
            'name' => $this->data['name'],
            'slug' => $this->data['slug']
        ]);

        // return new TenderCategory();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your category import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
