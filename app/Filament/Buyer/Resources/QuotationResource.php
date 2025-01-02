<?php

namespace App\Filament\Buyer\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Admin\Category;
use App\Models\Buyer\Quotation;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use App\Infolists\Components\Contact;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use App\Infolists\Components\Overview;
use App\Models\Admin\PrePopulatedData;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Infolists\Components\DocumentList;
use Filament\Tables\Filters\TernaryFilter;
use App\Infolists\Components\QuotationItem;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Enums\VerticalAlignment;
use Awcodes\TableRepeater\Components\TableRepeater;
use App\Filament\Buyer\Resources\QuotationResource\Pages;
use Guava\FilamentClusters\Forms\Cluster;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General')
                        ->icon('heroicon-m-squares-2x2')
                        ->schema([
                            TextInput::make('quotation_title')
                                ->placeholder('RFQ Title')
                                ->required()
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                ->columnSpanFull(),

                            Grid::make(2)
                                ->schema([
                                    Select::make('quotation_type')
                                        ->label('Quotation Type')
                                        ->options(
                                            PrePopulatedData::where('type', 'type_of_sourcing')
                                                ->where('data->type', 'Quotation')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable()
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                    
                                    Select::make('department_id')
                                        ->relationship('department', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->label('Department name')
                                                ->placeholder('Placeholder')
                                        ])
                                        ->createOptionModalHeading('Create new department')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                ]),
                            
                            TextInput::make('reference_no')
                                ->placeholder('MOESCHETQ24003387')
                                ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                ->columnSpanFull(),

                            Select::make('categories')
                                ->relationship(
                                    name: 'categories',
                                    titleAttribute: 'name', 
                                    modifyQueryUsing: function(Builder $query) {
                                        return $query->where('parent_id', '!=', -1);
                                    }
                                )
                                ->getSearchResultsUsing(function (string $search){
                                    return Category::where('parent_id', '!=', -1)
                                                        ->where('name', 'like', "%{$search}%")
                                                        ->pluck('name', 'id')
                                                        ->toArray();
                                })
                                ->preload()
                                ->multiple()
                                ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                ->columnSpanFull(),
                            
                            Grid::make(2)
                                ->schema([
                                    DateTimePicker::make('start_datetime')
                                        ->label('Start Date and Time')
                                        ->helperText('The default timezone is Cambodia (GMT+7)')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                        ->placeholder('Jul 27, 2024 13:02:00')
                                        ->native(false)
                                        ->minDate(function(Quotation $quotation, string $operation) {
                                            if($operation == 'edit') {
                                                return Carbon::parse($quotation->start_datetime);
                                            }
                                            return Carbon::now()->addDay();
                                        })
                                        ->maxDate(function(Quotation $quotation, string $operation) {
                                            if($operation == 'edit') {
                                                return Carbon::parse($quotation->start_datetime)->addYear();
                                            }
                                            return Carbon::now()->addYear();
                                        })
                                        ->live()
                                        ->afterStateUpdated(function (Set $set, Get $get) {
                                            $end_in_days = $get('end_in_days');
                                            if($end_in_days != null) {
                                                $set('end_datetime', Carbon::parse($get('start_datetime'))->addDays((int) $end_in_days));
                                            } else {
                                                $set('end_datetime', null);
                                            }
                                        }),

                                    Cluster::make([
                                        Select::make('end_in_days')
                                            ->placeholder('Days')
                                            ->options([
                                                7 => '7 Days',
                                                14 => '14 Days',
                                                21 => '21 Days',
                                                30 => '30 Days',
                                                60 => '60 Days',
                                                90 => '90 Days'
                                            ])
                                            ->native(false)
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, Get $get) {
                                                $set('end_datetime', Carbon::parse($get('start_datetime'))->addDays((int) $get('end_in_days')));
                                            }),

                                        DateTimePicker::make('end_datetime')
                                            ->label('End Date and Time')
                                            ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                            ->placeholder('Aug 28, 2024 12:00:00')
                                            ->native(false)
                                            ->minDate(fn(Get $get) => Carbon::parse($get('start_datetime'))->addDay())
                                            ->maxDate(fn(Get $get) => Carbon::parse($get('start_datetime'))->addYear()),
                                    ])
                                    ->label('End Date and Time')
                                    ->columns(3),
                                ]),

                            Section::make('Sourcing Information') 
                                ->schema([
                                    Grid::make([
                                            'default' => 2,
                                            'xs' => 1
                                        ])
                                        ->schema([
                                            Select::make('evaluation_type')
                                                ->label('Evaluation Type')
                                                ->options(
                                                    PrePopulatedData::where('type', 'evaluation_type')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable()
                                                ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                            
                                            Toggle::make('is_open_sourcing')
                                                ->label('Open Sourcing')
                                                ->onIcon('heroicon-m-check')
                                                ->offIcon('heroicon-m-x-mark')
                                                ->onColor(Color::Gray)
                                                ->default(true)
                                                ->inline(false)
                                                ->helperText('Turn off to add your selected bidders below')
                                                ->live()
                                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                        ]),

                                    Select::make('bidders')
                                        ->label('Select bidders')
                                        ->relationship(
                                            name: 'bidders', 
                                            titleAttribute: 'name',
                                            modifyQueryUsing: fn (Builder $query) => $query->role('supplier'),
                                        )
                                        ->preload()
                                        ->multiple()
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => ! $get('is_open_sourcing'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    Select::make('project_id')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->label('Project name')
                                                ->placeholder('Placeholder')
                                        ])
                                        ->createOptionModalHeading('Create new project')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    Select::make('mode_of_submission') 
                                        ->label('Mode of Submission')
                                        ->options(
                                            PrePopulatedData::where('type', 'submission_mode')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable()
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    Select::make('currency')
                                        ->label('Base Currency')
                                        ->options(
                                            PrePopulatedData::where('type', 'currency')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable()
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                ])
                                ->columns(3),

                            Section::make('Delivery Information') 
                                ->schema([
                                    TextInput::make('delivery_contact_person')
                                        ->label('Contact Person')
                                        ->placeholder('Placeholder')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    Textarea::make('delivery_address')
                                        ->label('Delivery Address')
                                        ->placeholder('Placeholder')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    // Grid::make(2)
                                    //     ->schema([
                                    //         Radio::make('is_partial_delivery')
                                    //             ->label('Partial Delivery?')
                                    //             ->options([
                                    //                 true => 'Yes',
                                    //                 false => 'No'
                                    //             ])
                                    //             ->default(true)
                                    //             ->inline()
                                    //             ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                                    //     ])
                                ]),
                            
                            Section::make('NDA') 
                                ->schema([
                                    Toggle::make('nda_required')
                                        ->label('Required NDA?')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark')
                                        ->onColor(Color::Gray)
                                        ->columnSpanFull()
                                        ->helperText('Upload the NDA file for supplier to download and sign')
                                        ->live()
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    FileUpload::make('nda_document')
                                        ->hiddenLabel()
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->helperText('Prefer to upload your NDA Document in PDF Document Format.')
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => $get('nda_required'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                ]),

                            Section::make('Briefing Information') 
                                ->schema([
                                    Toggle::make('briefing_information_required')
                                        ->label('Briefing Information Required?')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark')
                                        ->onColor(Color::Gray)
                                        ->columnSpanFull()
                                        ->live()
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                                    DatePicker::make('briefing_date')
                                        ->placeholder('Jul 27, 2024')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->native(false)
                                        ->visible(fn (Get $get): bool => $get('briefing_information_required'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                    TextInput::make('briefing_venue')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->placeholder('Venue Name')
                                        ->visible(fn (Get $get): bool => $get('briefing_information_required'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                    RichEditor::make('briefing_details')
                                        ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                                        ->placeholder('Briefing details')
                                        ->disableToolbarButtons([
                                            'strike',
                                            'codeBlock',
                                            'attachFiles'
                                        ])
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => $get('briefing_information_required'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                    FileUpload::make('briefing_documents')
                                        ->label('Briefing Documents')
                                        ->multiple()
                                        ->columnSpanFull()
                                        ->acceptedFileTypes([
                                            'application/pdf',
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                            'text/plain',
                                        ])
                                        ->helperText('Prefer to upload your Briefing Documents.')
                                        ->visible(fn (Get $get): bool => $get('briefing_information_required'))
                                        ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                                ])
                                ->columns(2)
                                ->collapsible(),
                        ])
                        ->columns(3),

                    Wizard\Step::make('Items')
                        ->icon('heroicon-m-list-bullet')
                        ->schema([
                            Repeater::make('quotationItems')
                                ->relationship()
                                ->hiddenLabel()
                                ->schema([
                                    Textarea::make('description')
                                        ->label('Description')
                                        ->placeholder('Description')
                                        ->columnSpanFull(),
                                    
                                    Grid::make(2)
                                        ->schema([
                                            Radio::make('type')
                                                ->label('Item Type?')
                                                ->options([
                                                    'goods' => 'Goods',
                                                    'services' => 'Services'
                                                ])
                                                ->default('goods')
                                                ->inline()
                                                ->live(),
                                        ]),
                                    
                                    TextInput::make('quantity')
                                        ->required()
                                        ->placeholder('Quantity')
                                        ->numeric()
                                        ->visible(fn(Get $get): bool => $get('type') == 'goods'),

                                    Select::make('uom')
                                        ->label('UOM')
                                        ->required()
                                        ->options(
                                            PrePopulatedData::where('type', 'uom')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable()
                                        ->visible(fn(Get $get): bool => $get('type') == 'goods'),

                                    Select::make('category_id')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->required()
                                        ->columnSpan(fn(Get $get) => $get('type') == 'goods' ? 1 : 3),

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('expected_delivery_date')
                                                ->label('Expected Delivery Date')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'expected_delivery_date')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('delivery_terms')
                                                ->label('Delivery Terms')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'delivery_term')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('payment_terms')
                                                ->label('Payment Terms')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'payment_term')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('payment_mode')
                                                ->label('Payment Mode')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'payment_mode')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),
                                        ]),

                                    Textarea::make('remark')
                                        ->label('Remark')
                                        ->placeholder('Placeholder')
                                        ->columnSpanFull(),

                                    // Section::make('Cost Guide')
                                    //     ->schema([
                                    //         TextInput::make('company_estimated_unit_price')
                                    //             ->placeholder('000.00')
                                    //             ->numeric(),

                                    //         TextInput::make('historical_unit_price')
                                    //             ->placeholder('000.00')
                                    //             ->numeric(),
                                    //     ])
                                    //     ->columns(2),

                                    // Section::make('Delivery Info')
                                    //     ->schema([
                                    //         Checkbox::make('same_as_header_address')
                                    //             ->default(true)
                                    //             ->live()
                                    //             ->columnSpanFull(),
                                            
                                    //         TextInput::make('delivery_contact_person')
                                    //             ->label('Contact Person Info')
                                    //             ->placeholder('Placeholder')
                                    //             ->disabled(fn (Get $get): bool => $get('same_as_header_address')),

                                    //         Textarea::make('delivery_address')
                                    //             ->placeholder('Placeholder')
                                    //             ->disabled(fn (Get $get): bool => $get('same_as_header_address')),
                                    //     ])
                                    //     ->columns(2)

                                ])
                                ->columns(3)
                                ->reorderable(false)
                                ->collapsed()
                                ->defaultItems(0)
                                ->addActionLabel('Add new')
                                ->itemLabel(fn (array $state): ?string => $state['description'] ?? null)
                                ->deleteAction(
                                    fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                                )
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),
                        ]),

                    Wizard\Step::make('Contacts')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            TableRepeater::make('quotationContacts')
                                ->relationship()
                                ->headers([
                                    Header::make('Contact Person'),
                                ])
                                ->schema([
                                    Select::make('contact_id')
                                        ->relationship('contact')
                                        ->getOptionLabelFromRecordUsing(function (Model $record) {
                                            $label = "<p> {$record->contact_person} </p>";
                                            if($record->designation) $label .= "<span class='text-xs text-slate-500'>- {$record->designation}</span><br>";
                                            if($record->phone) $label .= "<span class='text-xs text-slate-500'>- {$record->phone}</span><br>";
                                            if($record->email) $label .= "<span class='text-xs text-slate-500'>- {$record->email}</span><br>";

                                            return $label;
                                        })
                                        ->allowHtml()
                                        ->searchable()
                                        ->preload()
                                        // ->required()
                                        ->createOptionForm([
                                            Section::make([
                                                TextInput::make('contact_person')
                                                    ->required()
                                                    ->label('Contact Person')
                                                    ->placeholder('Elon Musk')
                                                    ->prefixIcon('heroicon-m-user'),
                                                TextInput::make('designation')
                                                    ->required()
                                                    ->label('Designation')
                                                    ->placeholder('CEO')
                                                    ->prefixIcon('heroicon-m-briefcase'),
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
                                            ])
                                            ->columns(2)
                                        ])
                                        ->createOptionModalHeading('Create new contact'),
                                ])
                                ->columnSpanFull()
                                ->defaultItems(0)
                                ->addActionLabel('Add contact')
                                ->deleteAction(
                                    fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                                )
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                        ]),

                    Wizard\Step::make('Documents')
                        ->icon('heroicon-m-paper-clip')
                        ->schema([
                            Repeater::make('documents')
                                ->relationship()
                                ->hiddenLabel()
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Name of file')
                                        ->live(onBlur: true)
                                        ->placeholder('Placeholder')
                                        ->required(),

                                    Select::make('document_type')
                                        ->label('Document Types')
                                        ->required()
                                        ->options(
                                            PrePopulatedData::where('type', 'document_type')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable(),
                                    
                                    FileUpload::make('document_path')
                                        ->label('Attach file')
                                        ->required()
                                        ->acceptedFileTypes([
                                            'application/pdf',
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                            'text/plain',
                                            'application/zip'
                                        ])
                                        ->helperText('Support Types: zip, docx, xlsx, pdf, pptx, txt.')
                                        ->directory('tender-documents')
                                        ->columnSpanFull(),

                                    Textarea::make('description')
                                        ->label('Description')
                                        ->placeholder('Placeholder')
                                        ->columnSpan(2),
                                ])
                                ->columns(2)
                                ->reorderable(false)
                                ->collapsed()
                                ->defaultItems(0)
                                ->addActionLabel('Add new document')
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                                ->deleteAction(
                                    fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                                )
                        ]),
                ])
                ->persistStepInQueryString()
                ->skippable(true)
                ->columnSpan(fn(string $operation) => $operation == 'create' ? 4 : 3),
                // ->skippable(fn(string $operation): bool => $operation === 'edit')

                Section::make([
                    CheckboxList::make('publication_check_list')
                            ->hiddenLabel()
                            ->required(fn(Get $get) => $get('quotation_state') != 'draft')
                            ->options([
                                'clearly_defined' => 'All requirements are clearly defined',
                                'documents_are_completed' => 'All tender documents are completed and checked in to the system',
                                'contract_terms_and_conditions' => 'All contract terms & conditions and contract compliance statements',
                                'rfp_terms_and_conditions' => 'All RFP terms & conditions and connected party dicisions',
                            ])
                            ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published'),

                    Section::make([
                            Radio::make('quotation_state')
                                ->hiddenLabel()
                                ->live()
                                ->options([
                                    'draft' => 'Draft',
                                    'review' => 'Review',
                                    // 'approved' => 'Approved for Publish',
                                    'published' => 'Publish'
                                ])
                                ->default('draft')
                                ->disabled(fn(string $operation, Get $get, Quotation $quotation):bool => $operation == 'edit' && $get('quotation_state') == 'published' && $quotation->quotation_state == 'published')
                        ])
                ])
                ->hiddenOn('create')
                ->columnSpan(1),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('reference_no')
                        ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class=" text-gray-400"> Quotation : '.$state.'</span>'))
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->searchable(),
                    
                    TextColumn::make('quotation_state')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'draft' => 'danger',
                            'review' => 'warning',
                            'published' => 'primary',
                        })
                        ->icons([
                            'heroicon-m-pencil-square' => 'draft',
                            'heroicon-m-arrow-path' => 'review',
                            'heroicon-m-check' => 'published',
                        ])
                        ->formatStateUsing(fn (string $state): string => match($state) {
                            'draft' => 'Draft',
                            'review' => 'In Review',
                            'published' => 'Published',
                        })
                        ->alignEnd()
                        ->verticalAlignment(VerticalAlignment::Start)
                ]),

                Split::make([
                    TextColumn::make('quotation_title')
                        ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-lg"> '.$state.'</span>'))
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->searchable()
                ]),

                Split::make([
                    Stack::make([
                        TextColumn::make('created_at')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Published : </span> <span>'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start),
                        
                        TextColumn::make('categories.name')
                            ->separator(',')
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Categories : </span> <span>'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start)
                            ->searchable(),
                    ]),

                    Stack::make([
                        TextColumn::make('end_datetime')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Closing Date : </span> <span class="text-green-500">'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start),
                        
                        TextColumn::make('mode_of_submission')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Mode of Submission : </span> <span class="">'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start)
                    ])
                ]),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
                    ->searchable(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable(),
                
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('evaluation_type')
                    ->options(
                        PrePopulatedData::where('type', 'evaluation_type')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),
                
                SelectFilter::make('mode_of_submission')
                    ->options(
                        PrePopulatedData::where('type', 'submission_mode')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),
                
                TernaryFilter::make('Sourcing')
                    ->placeholder('All')
                    ->trueLabel('Open sourcing')
                    ->falseLabel('Close sourcing')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_open_sourcing', true),
                        false: fn (Builder $query) => $query->where('is_open_sourcing', false),
                        blank: fn (Builder $query) => $query,
                    ),

                Filter::make('start_datetime')
                    ->label('Start date between')
                    ->form([
                        DatePicker::make('start_from'),
                        DatePicker::make('start_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_datetime', '>=', $date),
                            )
                            ->when(
                                $data['start_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_datetime', '<=', $date),
                            );
                    }),

                Filter::make('end_datetime')
                    ->label('End date between')
                    ->form([
                        DatePicker::make('end_from'),
                        DatePicker::make('end_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['end_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_datetime', '>=', $date),
                            )
                            ->when(
                                $data['end_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_datetime', '<=', $date),
                            );
                    }),

                Filter::make('nda_required')
                    ->label('NDA required')
                    ->toggle()
                    ->modifyFormFieldUsing(fn (Toggle $field) => $field->inline(false)),
            ])
            ->filtersFormWidth('4xl')
            ->filtersFormColumns(3)
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->recordUrl(
                function(Model $record) {
                    if($record->quotation_state == 'draft') {
                        return static::getUrl('edit', ['record' => $record]);
                    } else {
                        return static::getUrl('view', ['record' => $record]);
                    }
                }
            )
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('quotation_title')
                    ->hiddenLabel()
                    ->size(TextEntry\TextEntrySize::Large)
                    ->weight(FontWeight::Bold),

                Overview::make('General Information')
                    ->label('General Information')
                    ->schema([
                        TextEntry::make('quotation_type')
                            ->label('Quotation Type')
                            ->view('infolists.components.custom-entry'),
                        
                        TextEntry::make('department.name')
                            ->label('Department')
                            ->view('infolists.components.custom-entry'),
                        
                        TextEntry::make('reference_no')
                            ->label('Reference No.')
                            ->view('infolists.components.custom-entry'),
                            
                        TextEntry::make('categories.name')
                            ->label('Categories.')
                            ->view('infolists.components.custom-entry')
                            ->listWithLineBreaks()
                            ->bulleted(),

                        TextEntry::make('start_datetime')
                            ->label('Start Date and Time')
                            ->view('infolists.components.custom-entry')
                            ->dateTime(),

                        TextEntry::make('end_datetime')
                            ->label('End Date and Time')
                            ->view('infolists.components.custom-entry')
                            ->dateTime(),
                    ]),

                Overview::make('Sourcing Information')
                    ->label('Sourcing Information')
                    ->schema([
                        TextEntry::make('evaluation_type')
                            ->label('Evaluation Type')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('is_open_sourcing')
                            ->label('Type of Sourcing')
                            ->view('infolists.components.custom-entry')
                            ->formatStateUsing(function (string $state) {
                                if($state) {
                                    return "Open";
                                }
                                return 'Closed';
                            })
                            ->badge(),

                        TextEntry::make('project.name')
                            ->label('Sourcing For')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('mode_of_submission')
                            ->label('Model of Submission')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('currency')
                            ->label('Base Currency')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('nda_document')
                            ->label('NDA Document')
                            ->view('infolists.components.custom-entry')
                            ->formatStateUsing(function (string $state): HtmlString {
                                $url = '<div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <a href="'. Storage::url($state) .'" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">'. $state  .'</a>
                                    </div></div>';

                                return new HtmlString($url);
                            }),
                        
                    ]),

                Overview::make('Delivery Information')
                    ->label('Delivery Information')
                    ->schema([
                        TextEntry::make('delivery_contact_person')
                            ->label('Contact Person')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('delivery_address')
                            ->label('Delivery Address')
                            ->view('infolists.components.custom-entry'),

                        // TextEntry::make('is_partial_delivery')
                        //     ->label('Partial Delivery?')
                        //     ->formatStateUsing(function (string $state) {
                        //         if($state) {
                        //             return "Yes";
                        //         }
                        //         return 'No';
                        //     })
                        //     ->badge()
                        //     ->view('infolists.components.custom-entry'),
                    ]),

                Overview::make('Contact Information')
                    ->label('Contact Information')
                    ->schema([
                        Contact::make('contacts')
                            ->label('Contact')
                            ->hiddenLabel(),
                    ]),
                
                Overview::make('Briefing Information')
                    ->label('Briefing Information')
                    ->schema([
                        TextEntry::make('briefing_date')
                            ->label('Date')
                            ->view('infolists.components.custom-entry')
                            ->dateTime(),
                        TextEntry::make('briefing_venue')
                            ->label('Venue')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('briefing_details')
                            ->label('Description')
                            ->view('infolists.components.custom-entry')
                            ->html(),
                        TextEntry::make('briefing_documents')
                            ->label('Documents')
                            ->view('infolists.components.custom-entry')
                            ->listWithLineBreaks()
                            ->formatStateUsing(function (string $state) {
                                $url = '<div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <a href="'. Storage::url($state) .'" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">'. $state  .'</a>
                                    </div></div>';

                                return new HtmlString($url);
                            }),
                    ]),

                Overview::make('Line Items')
                    ->label('Line Items')
                    ->schema([
                            QuotationItem::make('quotationItems')
                                ->hiddenLabel()
                        ]),

                Overview::make('Documents')
                    ->label('Documents')
                    ->schema([
                        DocumentList::make('documents')
                            ->hiddenLabel()
                    ])

            ])
            ->columns(1);
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'bids' => Pages\ManageBids::route('/{record}/bids'),
            'awardings' => Pages\Awarding::route('/{record}/awardings'),
            'awardeds' => Pages\Awarded::route('/{record}/awardeds'),
            'addendums' => Pages\ManageAddendum::route('/{record}/addendums'),
            'questions' => Pages\ManageQuestion::route('/{record}/questions'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navitems = [
            Pages\ViewQuotation::class,
            Pages\EditQuotation::class,
            Pages\ManageAddendum::class,
            Pages\ManageBids::class,
            Pages\Awarding::class,
            Pages\Awarded::class,
            Pages\ManageQuestion::class
        ];

        return $page->generateNavigationItems($navitems);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
