<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ManageRelatedRecords;
use App\Filament\Buyer\Resources\QuotationResource;
use App\Models\TenderProposal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageBids extends ManageRelatedRecords
{
    protected static string $resource = QuotationResource::class;

    protected static string $relationship = 'quotationProposals';

    protected static ?string $navigationIcon = '';

    public static function getNavigationLabel(): string
    {
        return 'Bids';
    }

    public function getTitle(): string
    {
        return 'Quotation #' . $this->record->reference_no;
    }

    public function getSubheading(): ?string
    {
        return $this->record->department->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bidder.name')
                    ->label('Supplier'),
                Tables\Columns\TextColumn::make('bidder.info.company_name')
                    ->label('Company'),
                Tables\Columns\TextColumn::make('pricing')
                    ->label('Pricing')
                    ->default('NA'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')

                // Tables\Columns\SelectColumn::make('status')
                //     ->options([
                //         'proposed' => 'Proposed', 
                //         'cancelled' => 'Cancelled', 
                //         'nominated' => 'Nominated', 
                //         'disqualify' => 'Disqualify', 
                //         'awarded' => 'Awarded'
                //     ])
                
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
                    ->button()
                    ->outlined(),
                Action::make('Nominate')
                    ->icon('heroicon-o-squares-plus')
                    ->button()
                    ->outlined()
                    ->requiresConfirmation()
                    ->action(function (TenderProposal $record) {
                        $record->status = 'nominated';
                        $record->save();
                    })
                    ->disabled(function (TenderProposal $record) {
                        if($record->status == 'proposed' || $record->status == 'disqualify' || $record->status == 'awarded') {
                            return false;
                        };
                        return true;
                    }), //false for proposed, nominated and awarded
                    // ->hidden(fn (TenderProposal $record): bool => $record->status == 'awarded'),
                Action::make('Disqualify')
                    ->button()
                    ->outlined()
                    ->icon('heroicon-o-x-mark')
                    ->color(Color::Rose)
                    ->requiresConfirmation()
                    ->action(function (TenderProposal $record) {
                        $record->status = 'disqualify';
                        $record->save();
                    })
                    ->disabled(function (TenderProposal $record) {
                        if($record->status == 'proposed' || $record->status == 'nominated' || $record->status == 'awarded') {
                            return false;
                        };
                        return true;
                    }), //false for proposed, disqualify and awarded

                    // ->hidden(fn (TenderProposal $record): bool => $record->status == 'awarded'),
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
        return self::getResource()::getModel()::find(request()->route()->parameter('record'))?->quotationProposals()->count();
    }
}
