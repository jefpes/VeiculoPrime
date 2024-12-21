<?php

namespace App\Filament\Admin\Resources\PeopleResource\RelationManagers;

use App\Enums\Permission;
use App\Forms\Components\MoneyInput;
use App\Models\People;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};

class EmployeeRelationManager extends RelationManager
{
    protected static string $relationship = 'employee';

    protected static ?string $title = 'Contratos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                MoneyInput::make('salary')
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
                        $max30days  = strtotime($record->resignation_date) > now()->subDays(30)->timestamp;
                        $resignated = $record->resignation_date !== null;

                        //TODO: Conferir se há um contrato mais novo
                        return ($max30days && $resignated && auth_user()->hasAbility(Permission::EMPLOYEE_DELETE->value));
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
