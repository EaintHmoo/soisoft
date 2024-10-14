<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use App\Infolists\Components\Overview;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\SupplierResource\Pages;
use App\Filament\Buyer\Resources\SupplierResource\RelationManagers;
use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;

class SupplierResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Resources';

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
                TextColumn::make('No.')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('info.company_name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('info.business_type')
                    ->label('Business Type')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('Account Information')
                    ->description('Some description')
                    ->schema([
                        TextEntry::make('name')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('email')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('status')
                            ->default('-')
                            ->view('infolists.components.custom-entry')
                    ])
                    ->columnSpan(1),

                InfolistSection::make('Company Information')
                    ->description('Some description')
                    ->schema([
                        TextEntry::make('info.company_name')
                            ->label('Company Name')
                            ->default('-'),
                        TextEntry::make('info.supplier_type')
                            ->label('Supplier Type')
                            ->default('-'),
                        TextEntry::make('info.business_type')
                            ->label('Business Type')
                            ->default('-'),
                        TextEntry::make('info.registration_number')
                            ->label('Registration Number')
                            ->default('-'),
                        TextEntry::make('info.vat_number')
                            ->label('VAT Number')
                            ->default('-'),
                        TextEntry::make('info.supplier_industry')
                            ->label('Supplier Industry')
                            ->default('-'),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
                
                InfolistSection::make('Company Contact')
                    ->description('Some description')
                    ->schema([
                        TextEntry::make('info.company_contact_full_name')
                            ->label('Contact Name')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_designation')
                            ->label('Designation')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_email')
                            ->label('Email')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_address1')
                            ->label('Address')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_address2')
                            ->label('Address')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_province')
                            ->label('Province')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_city')
                            ->label('City')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_postal_code')
                            ->label('Postal Code')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.company_contact_country')
                            ->label('Country')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                    ])
                    ->columnSpan(1),

                InfolistSection::make('Primary Contact')
                    ->description('Some description')
                    ->schema([
                        TextEntry::make('info.primary_contact_full_name')
                            ->label('Contact Name')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_designation')
                            ->label('Designation')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_phone')
                            ->label('Phone')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_email')
                            ->label('Email')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_address1')
                            ->label('Address')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_address2')
                            ->label('Address')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_province')
                            ->label('Province')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_city')
                            ->label('City')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_postal_code')
                            ->label('Postal Code')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.primary_contact_country')
                            ->label('Country')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                    ])
                    ->columnSpan(1),

                InfolistSection::make('Individual Contact')
                    ->description('Some description')
                    ->schema([
                        TextEntry::make('info.individual_contact_full_name')
                            ->label('Contact Name')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.individual_contact_designation')
                            ->label('Designation')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.individual_contact_phone')
                            ->label('Phone')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.individual_contact_email')
                            ->label('Email')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('info.individual_contact_address')
                            ->label('Address')
                            ->default('-')
                            ->view('infolists.components.custom-entry'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(3);
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\SupplierDetail::route('/{record}'),
            // 'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {   
        return parent::getEloquentQuery()
                    ->orderBy('created_at', 'desc')
                    ->role('supplier');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::role('supplier')->count();
    }

    public static function getModelLabel(): string {
        return 'Supplier';
    }

    public static function getPluralModelLabel(): string {
        return 'Suppliers';
    }
}
