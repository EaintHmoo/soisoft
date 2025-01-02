<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TenderProposal;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use App\Filament\Buyer\Resources\TenderResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageBids extends ManageRelatedRecords
{
    protected static string $resource = TenderResource::class;

    protected static string $relationship = 'tenderProposals';

    protected static ?string $navigationIcon = '';

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['tender_state'] == 'published') {
            return true;
        }
        return false;
    }

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
                // Tables\Columns\TextColumn::make('pricing')
                //     ->label('Pricing')
                //     ->default('NA'),
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
                // Tables\Actions\ViewAction::make()
                //     ->label('Details')
                //     ->button()
                //     ->outlined(),
                Action::make('Details')
                    ->button()
                    ->outlined()
                    ->color('gray')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->infolist([
                        Section::make([
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('bidder.name'),
                            TextEntry::make('bidder.info.company_name')
                                ->label('Company'),
                            TextEntry::make('tender_fee_receipt')
                                ->formatStateUsing(function (string $state): HtmlString {
                                    $url = '<div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                            <a href="'. Storage::url($state) .'" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">'. $state  .'</a>
                                        </div></div>';

                                    return new HtmlString($url);
                                }),
                            TextEntry::make('proposal_comment'),
                            TextEntry::make('cancel_reason'),
                            TextEntry::make('cancel_comment')
                        ])
                        ->columns(2)
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false),
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
