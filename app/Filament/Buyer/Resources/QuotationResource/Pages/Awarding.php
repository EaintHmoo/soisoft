<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use Filament\Tables;
use Filament\Actions;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRelatedRecords;
use App\Filament\Buyer\Resources\QuotationResource;
use App\Models\QuotationProposal;
use App\Models\User;

class Awarding extends ManageRelatedRecords
{
    protected static string $resource = QuotationResource::class;

    protected static string $relationship = 'quotationProposals';

    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['quotation_state'] == 'published') {
            return true;
        }
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'nominated'))
            ->columns([
                Tables\Columns\TextColumn::make('bidder.name')
                    ->label('Supplier'),
                Tables\Columns\TextColumn::make('bidder.info.company_name')
                    ->label('Company'),
                Tables\Columns\TextColumn::make('pricing')
                    ->label('Pricing')
                    ->default('NA'),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Details')
                    ->button(),
                Action::make('Disqualify')
                    ->button()
                    ->outlined()
                    ->icon('heroicon-o-x-mark')
                    ->color(Color::Rose)
                    ->requiresConfirmation()
                    ->action(function (QuotationProposal $record) {
                        $record->status = 'disqualify';
                        $record->save();
                    })
                    ->disabled(fn (QuotationProposal $record): bool => $record->status == 'disqualify'),
                Action::make('Award')
                    ->icon('heroicon-o-trophy')
                    ->button()
                    ->outlined()
                    ->requiresConfirmation()
                    ->action(function (QuotationProposal $record) {
                        $record->status = 'awarded';
                        $record->save();
                        
                        $suppliers = User::whereIn('id', $this->record->quotationProposals()->pluck('bidder_id')->toArray())->get()->pluck('email')->toArray();
                        
                        //Send congratulation mail to bidder
                        $details = [
                            'title' => $this->record->quotation_title,
                            'category' => implode(', ', $this->record->categories()->pluck('name')->toArray()),
                            'supplier' =>  $record->bidder->name
                        ];
                        Mail::to([$record->bidder->email])->send(new \App\Mail\Awarded($details));

                        //Send to all participate suppliers
                        Mail::to($suppliers)->send(new \App\Mail\AwardedNotification($details));

                    })
                    ->disabled(fn (QuotationProposal $record): bool => $record->status == 'awarded'),
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DissociateAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make(),
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getResource()::getModel()::find(request()->route()->parameter('record'))
                ?->quotationProposals()
                ->where('status', 'nominated')
                ->count();
    }
}
