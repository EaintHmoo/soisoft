<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Filament\Support\Enums\MaxWidth;
use App\Models\Admin\PrePopulatedData;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\PrePopulatedDataResource\Pages;
use App\Filament\Admin\Resources\PrePopulatedDataResource\RelationManagers;
use Filament\Tables\Actions\ActionGroup;

class PrePopulatedDataResource extends Resource
{
    protected static ?string $model = PrePopulatedData::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->label('Select data type')
                    ->options(config('soisoft.data_types'))
                    ->required()
                    ->searchable(),

                Forms\Components\KeyValue::make('data')
                    ->required()
                    ->default([
                        'label' => null,
                        'description' => null
                    ])
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('type')
                    ->getTitleFromRecordUsing(fn (PrePopulatedData $record): string => config('soisoft.data_types.'.$record->type)),
            ])
            ->defaultGroup('type')
            ->columns([
                Tables\Columns\TextColumn::make('data.label')
                    ->label('Label')
                    ->searchable(),
                Tables\Columns\TextColumn::make('data.description')
                    ->label('Description')
                    ->grow()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options(config('soisoft.data_types'))
                    ->searchable()
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth(MaxWidth::TwoExtraLarge),
                    Tables\Actions\DeleteAction::make(),  
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePrePopulatedData::route('/'),
        ];
    }
}
