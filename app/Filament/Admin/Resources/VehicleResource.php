<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{FuelTypes, SteeringTypes, TransmissionTypes};
use App\Filament\Admin\Resources\VehicleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Models\{Accessory, Brand, Extra, People, VehicleType};
use App\Models\{Vehicle, VehicleModel};
use App\Tools\{Contracts, PhotosRelationManager};
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, FileUpload, Grid, Group, Section, Select, TextInput, Textarea};
use Filament\Forms\{Form, Get};
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\{Count, Sum};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Database\Eloquent\{Builder};
use Illuminate\Validation\Rules\Unique;
use PhpOffice\PhpWord\TemplateProcessor;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?int $navigationSort = 11;

    protected static ?string $recordTitleAttribute = 'plate';

    protected bool $validPlate = false;

    public static function getNavigationGroup(): ?string
    {
        return __('Vehicle');
    }

    public static function getModelLabel(): string
    {
        return __('Vehicle');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Vehicles');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    DatePicker::make('sold_date')->disabled(),
                    DatePicker::make('purchase_date')->required(),
                    Select::make('employee_id')
                        ->label('Buyer')
                        ->relationship('buyer', 'name', modifyQueryUsing: fn ($query, $record) => $query->orderBy('name')->whereHas('employee', fn ($query) => $query->where('resignation_date', null)->orWhere('id', $record?->buyer_id)))
                        ->optionsLimit(5)
                        ->searchable(),
                    Select::make('vehicle_model_id')
                        ->relationship('model', 'name')
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->relationship('supplier', 'name', modifyQueryUsing: fn ($query, $record) => $query->orderBy('name')->where('supplier', true)->orWhere('id', $record?->supplier_id))
                        ->preload()
                        ->optionsLimit(5)
                        ->searchable(),
                    MoneyInput::make('fipe_price'),
                    MoneyInput::make('purchase_price')
                        ->required(),
                    MoneyInput::make('sale_price')
                        ->required(),
                    MoneyInput::make('promotional_price'),
                    Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('year_one')
                                ->required()
                                ->label('Year'),
                            TextInput::make('year_two')
                                ->required()
                                ->label('Year (Model)'),
                        ]),
                    Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('km')
                                ->required()
                                ->numeric(),
                            TextInput::make('color')
                                ->required()
                                ->maxLength(255),
                        ]),
                    Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            Select::make('fuel')->options(FuelTypes::class),
                            TextInput::make('engine_power')
                                ->label('Motor')
                                ->required()
                                ->maxLength(30),
                        ]),
                    Select::make('steering')->options(SteeringTypes::class),
                    Select::make('transmission')
                        ->required()
                        ->options(TransmissionTypes::class),
                    Grid::make()
                        ->columnSpan(1)
                        ->schema([
                            TextInput::make('doors')
                                ->numeric(),
                            TextInput::make('seats')
                                ->label('Lugares')
                                ->numeric(),
                        ]),
                    Grid::make()
                        ->schema([
                            Select::make('accessories')
                                ->relationship('accessories', 'name')
                                ->multiple(),
                            Select::make('extras')
                                ->relationship('extras', 'name')
                                ->multiple(),
                        ]),
                    TextInput::make('plate')
                        ->required()
                        ->length(8)
                        ->mask('aaa-9*99')
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) => $rule->where('tenant_id', auth_user()?->tenant_id)->whereNull('sold_date')
                        ),
                    TextInput::make('chassi')
                        ->maxLength(255)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) => $rule->where('tenant_id', auth_user()?->tenant_id)->whereNull('sold_date')
                        ),
                    TextInput::make('renavam')
                        ->maxLength(255)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) => $rule->where('tenant_id', auth_user()?->tenant_id)->whereNull('sold_date')
                        ),
                    TextInput::make('crv_number')
                        ->label('CRV number')
                        ->maxLength(20)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) => $rule->where('tenant_id', auth_user()?->tenant_id)->whereNull('sold_date')
                        ),
                    TextInput::make('crv_code')
                        ->label('CRV code')
                        ->maxLength(20)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (Unique $rule) => $rule->where('tenant_id', auth_user()?->tenant_id)->whereNull('sold_date')
                        ),
                    Textarea::make('description')
                        ->maxLength(255)->columnSpanFull(),
                    Textarea::make('annotation')
                        ->maxLength(255)->columnSpanFull(),
                ])->columns(['sm' => 1, 'md' => 3, 'lg' => 4, 'xl' => 5]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->modifyQueryUsing(function ($query) {
                return $query->with('buyer', 'model', 'supplier');
            })
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                TextColumn::make('buyer.name')
                    ->label('Buyer')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('plate')
                    ->searchable(),
                TextColumn::make('model.name')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                TextColumn::make('full_year')
                    ->searchable()
                    ->sortable()
                    ->label('Year')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('accessories.name')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('extras.name')
                    ->badge()
                    ->listWithLineBreaks()
                    ->limitList(1)
                    ->expandableLimitedList()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('km')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('purchase_date')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Count::make()),
                TextColumn::make('fipe_price')
                    ->label('FIPE')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL')),
                TextColumn::make('purchase_price')
                    ->sortable()
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL')),
                TextColumn::make('sale_price')
                    ->sortable()
                    ->money('BRL')
                    ->summarize(Sum::make()->money('BRL')),
                TextColumn::make('promotional_price')
                    ->sortable()
                    ->money('BRL')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->summarize(Sum::make()->money('BRL')),
                TextColumn::make('model.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('supplier.name')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('fuel')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('engine_power')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('steering')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('transmission')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('doors')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('seats')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('chassi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('renavam')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('crv_number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('crv_code')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sold_date')
                ->date('d/m/Y')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('annotation')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filtersTriggerAction(fn (Tables\Actions\Action $action) => $action->slideOver())
            ->filtersFormWidth('xl')
            ->filters([
                Filter::make('filter')->form([
                    // Filtro de Datas de Compra
                    Group::make([
                        DatePicker::make('purchase_date_initial')
                            ->label('Purchase Date After'),
                        DatePicker::make('purchase_date_final')
                            ->label('Purchase Date Before'),
                    ])->columns(['sm' => 1, 'md' => 2]),

                    Grid::make()->columns(2)->schema([
                        // Filtro de Ano
                        Select::make('year_one')
                            ->label('After Year')
                            ->options(function () {
                                return Vehicle::query()
                                    ->select('year_one')
                                    ->distinct()
                                    ->orderBy('year_one')
                                    ->pluck('year_one', 'year_one')
                                    ->toArray();
                            }),
                        // Filtro de Tipo de Veículo
                        Select::make('type')
                            ->label('Type')
                            ->options(VehicleType::query()->orderBy('name')->get()->pluck('name', 'id'))
                            ->live(),

                        // Filtro de Marca
                        Select::make('brand')
                            ->label('Brand')
                            ->options(Brand::query()->orderBy('name')->whereHas('models', fn ($query) => $query->whereHas('vehicles'))->get()->pluck('name', 'id'))
                            ->searchable()
                            ->optionsLimit(5)
                            ->preload()
                            ->live(),

                        // Filtro de Modelo
                        Select::make('model')
                            ->label('Model')
                            ->options(
                                fn (Get $get) => VehicleModel::query()
                                    ->orderBy('name')
                                    ->whereHas('vehicles')
                                    ->when($get('type'), fn ($query, $value) => $query->where('vehicle_type_id', $value))
                                    ->when($get('brand'), fn ($query, $value) => $query->where('brand_id', $value))
                                    ->get()
                                    ->pluck('name', 'id')
                            )
                            ->searchable()
                            ->optionsLimit(5)
                            ->live(),
                    ]),

                    // Filtro acessórios
                    Select::make('accessories')
                            ->label('Accessories')
                            ->options(
                                fn () => Accessory::query()
                                    ->orderBy('name')
                                    ->get()
                                    ->pluck('name', 'id')
                            )
                            ->multiple()
                            ->live(),

                    // Filtro extras
                    Select::make('extras')
                            ->label('Extras')
                            ->options(
                                fn () => Extra::query()
                                    ->orderBy('name')
                                    ->get()
                                    ->pluck('name', 'id')
                            )
                            ->multiple()
                            ->live(),

                    // Filtro de Fornecedor
                    Select::make('supplier')
                        ->label('Supplier')
                        ->options(People::query()->whereHas('vehiclesAsSupplier')->orWhere('supplier', true)->pluck('name', 'id'))
                        ->searchable()
                        ->optionsLimit(5),

                    // Filtro de Comprador
                    Select::make('buyer')
                        ->label('Buyer')
                        ->options(People::query()->whereHas('vehiclesAsBuyer')->orWhere('supplier', true)->pluck('name', 'id'))
                        ->optionsLimit(5)
                        ->searchable(),
                ])->query(function (Builder $query, array $data): Builder {
                    return $query
                        // Aplica o filtro de data de compra
                        ->when($data['purchase_date_initial'], fn ($query, $value) => $query->where('purchase_date', '>=', $value))
                        ->when($data['purchase_date_final'], fn ($query, $value) => $query->where('purchase_date', '<=', $value))

                        // Aplica o filtro de ano
                        ->when($data['year_one'], fn ($query, $value) => $query->where('year_one', '>=', $value))

                        // Aplica o filtro de tipo
                        ->when(
                            $data['type'],
                            fn ($query, $value) => $query->whereHas('model', fn ($query) => $query->where('vehicle_type_id', $value))
                        )

                        // Aplica o filtro de marca
                        ->when(
                            $data['brand'],
                            fn ($query, $value) => $query->whereHas('model', fn ($query) => $query->where('brand_id', $value))
                        )

                        // Aplica o filtro de modelo
                        ->when($data['model'], fn ($query, $value) => $query->where('vehicle_model_id', $value))

                        // Aplica o filtro de acessórios
                        ->when($data['accessories'], fn ($query, $value) => $query->whereHas('accessories', fn ($query) => $query->whereIn('accessory_id', $value)))

                        // Aplica o filtro de extras
                        ->when($data['extras'], fn ($query, $value) => $query->whereHas('extras', fn ($query) => $query->whereIn('extra_id', $value)))

                        // Aplica o filtro de fornecedor
                        ->when($data['supplier'], fn ($query, $value) => $query->where('supplier_id', $value))

                        // Aplica o filtro de comprador
                        ->when($data['buyer'], fn ($query, $value) => $query->where('buyer_id', $value));
                })->indicateUsing(function (array $data): array {
                    $indicators = [];

                    // Indicadores para as datas de compra
                    if ($data['purchase_date_initial'] ?? null) {
                        $indicators[] = __('Purchase Date After: ') . Carbon::parse($data['purchase_date_initial'])->format('d/m/Y');
                    }

                    if ($data['purchase_date_final'] ?? null) {
                        $indicators[] = __('Purchase Date Before: ') . Carbon::parse($data['purchase_date_final'])->format('d/m/Y');
                    }

                    // Indicador para o ano
                    if ($data['year_one'] ?? null) {
                        $indicators[] = __('After Year') . ': ' . $data['year_one'];
                    }

                    // Indicadores para tipo, marca e modelo
                    if ($data['type'] ?? null) {
                        $indicators[] = __('Type') . ': ' . VehicleType::query()->find($data['type'])->name;
                    }

                    if ($data['brand'] ?? null) {
                        $indicators[] = __('Brand') . ': ' . Brand::query()->find($data['brand'])->name;
                    }

                    if ($data['model'] ?? null) {
                        $indicators[] = __('Model') . ': ' . VehicleModel::query()->find($data['model'])->name;
                    }

                    // Indicador para o fornecedor
                    if ($data['supplier'] ?? null) {
                        $indicators[] = __('Supplier') . ': ' . People::query()->find($data['supplier'])->name;
                    }

                    // Indicador para o comprador
                    if ($data['buyer'] ?? null) {
                        $indicators[] = __('Buyer') . ': ' . People::query()->find($data['buyer'])->name;
                    }

                    return $indicators;
                }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->visible(fn ($record) => !$record->sold_date),
                Tables\Actions\Action::make('contract')
                    ->requiresConfirmation()
                    ->modalHeading(__('Contract'))
                    ->label('Contract')
                    ->translateLabel()
                    ->icon('heroicon-o-document')
                    ->iconSize('md')
                    ->color('info')
                    ->form([
                        FileUpload::make('contract')
                            ->label('Contract')
                            ->panelAspectRatio('2:1')
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]),
                    ])
                    ->action(function (array $data, Vehicle $vehicle) {

                        $template = new TemplateProcessor($data['contract']->getRealPath());

                        $caminho = Contracts::generatePurchaseContract($template, $vehicle);

                        return response()->download($caminho)->deleteFileAfterSend(true);
                    })->visible(fn (Vehicle $vehicle): bool => $vehicle->supplier()->exists()), //@phpstan-ignore-line
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PhotosRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view'   => Pages\ViewVehicle::route('/{record}'),
            'edit'   => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
