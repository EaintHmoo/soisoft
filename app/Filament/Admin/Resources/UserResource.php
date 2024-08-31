<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use Filament\Forms\Components\Section;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Forms\Components\FileUpload::make('profile_picture')
                        ->image()
                ])->columnSpan(1),
                Section::make([
                    Forms\Components\TextInput::make('name')
                        ->placeholder('Full Name')
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->placeholder('support@soisoft.com')
                        ->unique(ignoreRecord: true)
                        ->required(),
                    
                    Forms\Components\TextInput::make('password')
                        ->placeholder('*****')
                        ->password()
                        ->revealable()
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                    
                    Forms\Components\Select::make('roles')
                        ->multiple()
                        ->relationship('roles', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Select roles')
                        ->columnSpan(2),
                ])->columnSpan(2)->columns(2)
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('ID'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('profile_picture')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl('https://redthread.uoregon.edu/files/large/affd16fd5264cab9197da4cd1a996f820e601ee4.jpg'),
                Tables\Columns\TextColumn::make('name')
                    ->grow()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->separator(','),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
