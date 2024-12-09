<?php

namespace App\Filament\Admin\Resources;

use App\Enums\{FuelTypes, SteeringTypes, TransmissionTypes};
use App\Filament\Admin\Resources\VehicleResource\RelationManagers\{DocPhotosRelationManager, PhotosRelationManager};
use App\Filament\Admin\Resources\VehicleResource\{Pages};
use App\Forms\Components\MoneyInput;
use App\Helpers\Contracts;
use App\Models\{Brand, VehicleType};
use App\Models\{Employee, Supplier, Vehicle, VehicleModel};
use Carbon\Carbon;
use Filament\Forms\Components\{DatePicker, FileUpload, Group, Section, Select, TextInput, Textarea};
use Filament\Forms\{Form, Get};
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\{Count, Sum};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\{Tables};
use Illuminate\Database\Eloquent\{Builder};
use PhpOffice\PhpWord\TemplateProcessor;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?int $navigationSort = 11;

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
                    Select::make('employee_id')
                        ->label('Buyer')
                        ->options(Employee::whereNull('resignation_date')->pluck('name', 'id')) //@phpstan-ignore-line
                        ->optionsLimit(5)
                        ->searchable(),
                    DatePicker::make('purchase_date')
                        ->required(),
                    MoneyInput::make('fipe_price'),
                    MoneyInput::make('purchase_price')
                        ->required(),
                    MoneyInput::make('sale_price')
                        ->required(),
                    MoneyInput::make('promotional_price'),
                    Select::make('vehicle_model_id')
                        ->relationship('model', 'name'),
                    Select::make('supplier_id')
                        ->label('Supplier')
                        ->options(Supplier::query()->pluck('name', 'id'))
                        ->optionsLimit(5)
                        ->searchable(),
                    TextInput::make('year_one')
                        ->required()
                        ->label('Year'),
                    TextInput::make('year_two')
                        ->required()
                        ->label('Year (Model)'),
                    TextInput::make('km')
                        ->required()
                        ->numeric(),
                    Select::make('fuel')
                        ->options(collect(FuelTypes::cases())->mapWithKeys(fn (FuelTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    TextInput::make('engine_power')
                        ->required()
                        ->maxLength(30),
                    Select::make('steering')
                        ->options(collect(SteeringTypes::cases())->mapWithKeys(fn (SteeringTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    Select::make('transmission')
                        ->required()
                        ->options(collect(TransmissionTypes::cases())->mapWithKeys(fn (TransmissionTypes $status) => [
                            $status->value => $status->value,
                        ])->toArray()),
                    TextInput::make('doors')
                        ->numeric(),
                    TextInput::make('seats')
                        ->numeric(),
                    TextInput::make('traction')
                        ->maxLength(255),
                    TextInput::make('color')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('plate')
                        ->required()
                        ->maxLength(255)
                        ->mask('aaa-9*99'),
                    TextInput::make('chassi')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('renavam')
                        ->required()
                        ->maxLength(255),
                    DatePicker::make('sold_date')->disabled(),
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
                return $query->with('employee', 'model', 'supplier');
            })
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Tenant')
                    ->visible(fn () => auth_user()->tenant_id === null)
                    ->sortable(),
                TextColumn::make('employee.name')
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
                    ->label('Year'),
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
                    ->sortable(),
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
                TextColumn::make('traction')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('chassi')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('renavam')
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
            ->filters([
                Filter::make('filter')->form([
                    // Filtro de Datas de Compra
                    Group::make([
                        DatePicker::make('purchase_date_initial')
                            ->label('Purchase Date After'),
                        DatePicker::make('purchase_date_final')
                            ->label('Purchase Date Before'),
                    ])->columns(['sm' => 1, 'md' => 2]),

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
                        ->options(VehicleType::all()->pluck('name', 'id'))
                        ->live(),

                    // Filtro de Marca
                    Select::make('brand')
                        ->label('Brand')
                        ->options(Brand::all()->pluck('name', 'id'))
                        ->searchable()
                        ->optionsLimit(5)
                        ->preload()
                        ->live(),

                    // Filtro de Modelo
                    Select::make('model')
                        ->label('Model')
                        ->options(function (Get $get) {
                            return VehicleModel::query()
                                ->orderBy('name')
                                ->when($get('type'), fn ($query, $value) => $query->where('vehicle_type_id', $value))
                                ->when($get('brand'), fn ($query, $value) => $query->where('brand_id', $value))
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->optionsLimit(5)
                        ->live(),

                    // Filtro de Fornecedor
                    Select::make('supplier')
                        ->label('Supplier')
                        ->options(Supplier::all()->pluck('name', 'id'))
                        ->searchable()
                        ->optionsLimit(5),
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

                        // Aplica o filtro de fornecedor
                        ->when($data['supplier'], fn ($query, $value) => $query->where('supplier_id', $value));
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
                        $indicators[] = __('Supplier') . ': ' . Supplier::query()->find($data['supplier'])->name;
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
            DocPhotosRelationManager::class,
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
