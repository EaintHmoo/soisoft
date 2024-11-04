<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\Mail;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Buyer\Resources\TenderResource;

class EditTender extends EditRecord
{
    protected static string $resource = TenderResource::class;

    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['tender_state'] == 'draft') {
            return false;
        }
        return true;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Submit')
                ->label(function() {
                    if($this->data['tender_state'] == 'published' && $this->record->tender_state != 'published') return "Publish";
                    return "Save";
                })
                ->icon('heroicon-m-check-circle')
                ->requiresConfirmation(function() {
                    if($this->data['tender_state'] == 'published') return true;
                    return false;
                })
                ->action(function () {
                    $this->closeActionModal();
                    $this->save();
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
        // if($this->record->tender_state == 'published') {
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
