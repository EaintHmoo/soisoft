<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Filament\Buyer\Resources\QuotationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;
    protected static ?string $navigationIcon = '';

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Save changes')
                ->icon('heroicon-m-check-circle')
                ->formId('form'),
            $this->getCancelFormAction()
                ->icon('heroicon-m-x-circle')
                ->formId('form'),
            Actions\DeleteAction::make()
                ->icon('heroicon-m-trash'),
        ];
    }
    

    protected function getFormActions(): array
    {
        return [];
    }

    public static function getNavigationLabel(): string
    {
        return 'Edit';
    }
}
