<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Buyer\Resources\QuotationResource;

class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;
    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['quotation_state'] == 'draft') {
            return false;
        }
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Submit')
                ->label(function() {
                    if($this->data['quotation_state'] == 'published' && $this->record->quotation_state != 'published') return "Publish";
                    return "Save";
                })
                ->icon('heroicon-m-check-circle')
                ->action(function () {
                    $this->closeActionModal();
                    $this->save();
                })
                ->submit(null)
                ->requiresConfirmation(function() {
                    if($this->data['quotation_state'] == 'published') return true;

                    return false;
                }),

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

    protected function afterSave(): void
    {
        // if($this->record->quotation_state == 'published') {
        //     //Send email
        //     $details = [
        //         'type' => $this->record->quotation_type,
        //         'title' => $this->record->quotation_title,
        //         'start_date' => $this->record->start_datetime,
        //         'end_date' => $this->record->end_datetime,
        //     ];

        //     $contacts = User::role('supplier')->pluck('email')->toArray();
        
        //     Mail::to($contacts)->send(new \App\Mail\NewTender($details));
        // }

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
}
