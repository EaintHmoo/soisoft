<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TenderProposal;
use Filament\Support\Colors\Color;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Buyer\Resources\TenderResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageBids extends ManageRelatedRecords
{
    protected static string $resource = TenderResource::class;

    protected static string $relationship = 'tenderProposals';

    protected static ?string $navigationIcon = '';

    public static function getNavigationLabel(): string
    {
        return 'Bids';
    }

    public function getTitle(): string
    {
        return 'Tender #' . $this->record->tender_no;
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
            // ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('bidder.name'),
                Tables\Columns\TextColumn::make('bidder.info.company_name')
                    ->label('Company'),
                Tables\Columns\TextColumn::make('pricing')
                    ->label('Pricing')
                    ->default('NA'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
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
                    }),
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
        return self::getResource()::getModel()::find(request()->route()->parameter('record'))?->tenderProposals()->count();
    }
}
