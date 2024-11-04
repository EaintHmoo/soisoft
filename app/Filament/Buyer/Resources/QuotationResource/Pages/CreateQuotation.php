<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Support\Colors\Color;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Buyer\Resources\QuotationResource;

class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
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

    protected function afterCreate(): void
    {
        if($this->record->quotation_state == 'published') {
            $quotation = $this->record;

            $details = [
                'title' => $quotation->quotation_title,
                'category' => implode(', ', $quotation->categories()->pluck('name')->toArray()),
                'deadline' => $quotation->end_datetime,
                'url' => 'https://mptc.soisoft.com/quotations/'.$quotation->id 
            ];

            $contacts = User::role('supplier')->pluck('email')->toArray();
        
            Mail::to($contacts)->send(new \App\Mail\NewTender($details));
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['quotation_state'] = 'draft';
        // dd(array_filter($data));
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}
