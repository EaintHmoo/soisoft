<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\Buyer\Contact;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\ContactResource\Pages;
use App\Filament\Buyer\Resources\ContactResource\RelationManagers;
use Illuminate\Support\HtmlString;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?string $recordTitleAttribute = 'contact_person';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('contact_person')
                    ->required()
                    ->label('Contact Person')
                    ->placeholder('Elon Musk')
                    ->prefixIcon('heroicon-m-user')
                    ->live(onBlur: true),
                TextInput::make('designation')
                    ->required()
                    ->label('Designation')
                    ->placeholder('CEO'),
                TextInput::make('phone')
                    ->label('Phone Number')
                    ->placeholder('+66 111222333')
                    ->tel()
                    ->prefixIcon('heroicon-m-phone'),
                TextInput::make('email')
                    ->label('Email Address')
                    ->placeholder('elon@spacex.com')
                    ->email()
                    ->prefixIcon('heroicon-m-at-symbol'),
                RichEditor::make('address')
                    ->label('Address')
                    ->disableToolbarButtons([
                        'strike',
                        'codeBlock',
                        'attachFiles'
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contact_person')
                    ->label('Name')
                    ->grow()
                    ->searchable()
                    ->description(fn (Contact $record): HtmlString => new HtmlString('<div class="text-xs text-gray-500">'.$record->address.'</div>')),
                TextColumn::make('designation')
                    ->grow()
                    ->searchable(),
                TextColumn::make('phone')
                    ->grow()
                    ->searchable(),
                TextColumn::make('email')
                    ->grow()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageContacts::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['contact_person', 'phone', 'email'];
    }
}
