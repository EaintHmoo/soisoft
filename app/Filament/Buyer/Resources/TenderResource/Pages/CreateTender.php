<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
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
            // Actions\Action::make('Preview')
            //     ->icon('heroicon-m-document-text')
            //     ->color(Color::Orange)
            //     ->modalSubmitAction(false) //Remove Submit Button
            //     ->modalCancelAction(false) 
            //     ->action(fn () => $this->form->getState())
            //     ->modalContent(
            //         fn($record): View => view('tender.tender-preview', ['record' => $record])
            //     ),
            
            $this->getCreateFormAction()
                ->label('Save Draft')
                ->color(Color::Yellow)
                ->icon('heroicon-m-pencil-square')
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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['tender_state'] = 'draft';
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }

    protected function afterCreate(): void
    {
        // $details = [
        //     'type' => 'Tender',
        //     'title' => $this->record->tender_title,
        //     'start_date' => $this->record->start_datetime,
        //     'end_date' => $this->record->end_datetime,
        // ];
        if($this->record->tender_state == 'published') {
            $tender = $this->record;

            $details = [
                'title' => $tender->tender_title,
                'category' => $tender->category->name,
                'deadline' => $tender->end_datetime,
                'url' => 'https://mptc.soisoft.com/tenders/'.$tender->id 
            ];

            $contacts = User::role('supplier')->pluck('email')->toArray();
        
            Mail::to($contacts)->send(new \App\Mail\NewTender($details));
        }
    }
}
