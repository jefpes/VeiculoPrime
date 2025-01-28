<?php

namespace App\Filament\Master\Resources\PeopleResource\RelationManagers;

use App\Enums\Permission;
use App\Models\People;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Leandrocfe\FilamentPtbrFormFields\Money;

class EmployeeRelationManager extends RelationManager
{
    protected static string $relationship = 'employee';

    protected static ?string $title = 'Contratos de Trabalho';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Money::make('salary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('admission_date')
                    ->required(),
                Forms\Components\DatePicker::make('resignation_date')
                    ->readOnly(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('salary')->money('BRL'),
                Tables\Columns\TextColumn::make('admission_date')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('resignation_date')->date('d/m/Y'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('New contract')
                ->authorize(function () {
                    $hasContract = People::find($this->getOwnerRecord()->id)->employee()->where('resignation_date', null)->get()->count() === 0; //@phpstan-ignore-line
                    $hasAbility  = auth_user()->hasAbility(Permission::EMPLOYEE_CREATE->value);

                    return $hasAbility && $hasContract;
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->authorize(function ($record) {
                    return $record->resignation_date === null && auth_user()->hasAbility(Permission::EMPLOYEE_UPDATE->value);
                }),
                Tables\Actions\Action::make('dismiss')
                ->label('Dismiss')
                ->icon('heroicon-o-arrow-left-start-on-rectangle')
                ->color('danger')
                ->authorize(function ($record) {
                    return $record->resignation_date === null && auth_user()->hasAbility(Permission::EMPLOYEE_DELETE->value);
                })
                ->requiresConfirmation()
                ->form([
                    Forms\Components\DatePicker::make('resignation_date')
                        ->label('Resignation Date'),
                ])
                ->action(function ($record, array $data) {
                    if ($record->people->user !== null) {
                        $record->people->user->delete();
                    }

                    $record->update(['resignation_date' => ($data['resignation_date'] ?? now())]);
                }),

                Tables\Actions\Action::make('rehire')
                    ->label('Rehire')
                    ->icon('heroicon-o-arrow-left-end-on-rectangle')
                    ->color('warning')
                    ->authorize(function ($record) {
                        // Verificar se a demissão foi nos últimos 30 dias
                        $wasResignedRecently = strtotime($record->resignation_date) > now()->subDays(30)->timestamp;

                        // Verificar se o registro foi de fato demitido
                        $isResigned = $record->resignation_date !== null;

                        // Obter a pessoa associada ao registro
                        $people = People::query()->find($record->people_id);

                        // Verificar se há contrato ativo
                        $hasActiveContract = $people->employee()->whereNull('resignation_date')->exists();

                        // Obter o contrato mais recente
                        $lastContract = $people->employee()->latest('created_at')->first();

                        // Verificar se o contrato atual é o mais recente
                        $isLastContract = $lastContract?->id === $record->id; //@phpstan-ignore-line

                        // Retornar se todas as condições são atendidas
                        return $wasResignedRecently
                            && $isResigned
                            && auth_user()->hasAbility(Permission::EMPLOYEE_DELETE->value)
                            && !$hasActiveContract
                            && $isLastContract;
                    })
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        if ($record->user !== null) {
                            $record->user->restore();
                        }

                        $record->update(['resignation_date' => null]);
                    }),
            ]);
    }
}
