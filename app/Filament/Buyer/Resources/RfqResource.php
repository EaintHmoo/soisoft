<?php

namespace App\Filament\Buyer\Resources;

use App\Filament\Buyer\Resources\RfqResource\Pages;
use App\Filament\Buyer\Resources\RfqResource\RelationManagers;
use App\Models\Buyer\Tender;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RfqResource extends Resource
{
    protected static ?string $model = Tender::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'RFQ';

    protected static ?string $modelLabel = 'RFQ';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRfqs::route('/'),
            'create' => Pages\CreateRfq::route('/create'),
            'edit' => Pages\EditRfq::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
