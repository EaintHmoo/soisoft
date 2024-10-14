<?php

namespace App\Filament\Buyer\Resources\SupplierResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Buyer\Resources\SupplierResource;

class SupplierDetail extends ViewRecord
{
    protected static string $resource = SupplierResource::class;

    public function getTitle(): string
    {
        return 'View #' . $this->record->info->company_name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = 'approved';
                    $this->record->save();

                    $this->refreshFormData([
                        'status',
                    ]);

                    Notification::make()
                        ->title('Approved')
                        ->success()
                        ->send();
                })
                ->disabled(function () {
                    if($this->record->status == 'approved') {
                        return true;
                    };
                    return false;
                }),

            Action::make('reject')
                ->label('Reject')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->status = 'rejected';
                    $this->record->save();

                    $this->refreshFormData([
                        'status',
                    ]);

                    Notification::make()
                        ->title('Rejected')
                        ->danger()
                        ->send();
                })
                ->color(Color::Rose)
                ->disabled(function () {
                    if($this->record->status == 'rejected') {
                        return true;
                    };
                    return false;
                }),
        ];
    }
}
