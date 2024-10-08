<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Buyer\Resources\TenderResource;

class CreateTender extends CreateRecord
{
    protected static string $resource = TenderResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     $data['buyer_id'] = auth()->id();
    //     $data['tender_type'] = 'Tender';
    
    //     return $data;
    // }

    // protected function getRedirectUrl(): string
    // {
    //     return $this->getResource()::getUrl('index');
    // }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Preview')
                ->icon('heroicon-m-document-text')
                ->color(Color::Orange)
                ->modalSubmitAction(false) //Remove Submit Button
                ->modalCancelAction(false) 
                ->action(fn () => $this->form->getState())
                ->modalContent(
                    fn($record): View => view('tender.tender-preview', ['record' => $record])
                ),
            
            $this->getCreateFormAction()
                ->label('Submit')
                ->icon('heroicon-m-check-circle')
                ->formId('form'),
            $this->getCancelFormAction()
                ->icon('heroicon-m-x-circle')
                ->formId('form'),
        ];
    }
    

    protected function getFormActions(): array
    {
        return [];
    }
}
