<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use App\Filament\Buyer\Resources\TenderResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Awarded extends ManageRelatedRecords
{
    protected static string $resource = TenderResource::class;

    protected static string $relationship = 'tenderProposals';

    protected static ?string $navigationIcon = '';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'awarded'))
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
                ->where('status', 'awarded')
                ->count();
    }
}
