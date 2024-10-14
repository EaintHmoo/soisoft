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
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Buyer\Resources\TenderResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Awarding extends ManageRelatedRecords
{
    protected static string $resource = TenderResource::class;

    protected static string $relationship = 'tenderProposals';

    protected static ?string $navigationIcon = '';

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
                    ->action(function (TenderProposal $record) {
                        $record->status = 'disqualify';
                        $record->save();
                    })
                    ->disabled(fn (TenderProposal $record): bool => $record->status == 'disqualify'),
                Action::make('Award')
                    ->icon('heroicon-o-trophy')
                    ->button()
                    ->outlined()
                    ->requiresConfirmation()
                    ->action(function (TenderProposal $record) {
                        $record->status = 'awarded';
                        $record->save();
                    })
                    ->disabled(fn (TenderProposal $record): bool => $record->status == 'awarded'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getResource()::getModel()::find(request()->route()->parameter('record'))
                ?->tenderProposals()
                ->where('status', 'nominated')
                ->count();
    }
}
