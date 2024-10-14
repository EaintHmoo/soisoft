<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Buyer\Resources\QuotationResource;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;

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

    protected function afterCreate(): void
    {
        $details = [
            'type' => $this->record->quotation_type,
            'title' => $this->record->quotation_title,
            'start_date' => $this->record->start_datetime,
            'end_date' => $this->record->end_datetime,
        ];

        $contacts = User::role('supplier')->pluck('email')->toArray();
       
        Mail::to($contacts)->send(new \App\Mail\NewTender($details));
    }
}
