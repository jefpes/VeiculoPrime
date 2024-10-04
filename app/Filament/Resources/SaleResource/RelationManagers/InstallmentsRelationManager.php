<?php

namespace App\Filament\Resources\SaleResource\RelationManagers;

use App\Enums\PaymentMethod;
use App\Forms\Components\MoneyInput;
use App\Models\PaymentInstallments;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;

class InstallmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'paymentInstallments';

    protected static ?string $title = 'Parcelas';

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('sale_id')
            ->columns([
                Tables\Columns\TextColumn::make('due_date')->date('d/m/Y'),
                Tables\Columns\TextColumn::make('value')->money('BRL'),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'PENDENTE'    => 'info',
                        'REEMBOLSADO' => 'warning',
                        'CANCELADO'   => 'danger',
                        default       => 'success',
                    }),
                Tables\Columns\TextColumn::make('discount')->money('BRL')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('surcharge')->money('BRL')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_date')->date('d/m/Y')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_value')->money('BRL'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('receive')
                    ->translateLabel()
                    ->icon('heroicon-o-banknotes')
                    ->slideOver()
                    ->requiresConfirmation()
                    ->modalHeading(__('Receive installment'))
                    ->modalDescription(null)
                    ->modalIcon('heroicon-o-banknotes')
                    ->fillForm(fn (PaymentInstallments $record): array => [
                        'value'        => $record->value, // @phpstan-ignore-line
                        'payment_date' => now(),
                    ])
                    ->form([
                        MoneyInput::make('value')->readOnly(),
                        Forms\Components\Select::make('payment_method')
                            ->options(
                                collect(PaymentMethod::cases())
                                    ->mapWithKeys(fn (PaymentMethod $type) => [$type->value => ucfirst($type->value)])
                            )
                            ->required(),
                        MoneyInput::make('payment_value')->required(),
                        Forms\Components\DatePicker::make('payment_date')->required(),
                    ])->action(function (PaymentInstallments $installment, array $data) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PAGO',
                            'discount'       => $data['payment_value'] < $installment->value ? ($installment->value - $data['payment_value']) : 0, //@phpstan-ignore-line
                            'surcharge'      => $data['payment_value'] > $installment->value ? ($data['payment_value'] - $installment->value) : 0,
                            'payment_date'   => $data['payment_date'],
                            'payment_value'  => $data['payment_value'],
                            'payment_method' => $data['payment_method'],
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment received'))
                            ->send();
                    })->visible(fn (PaymentInstallments $installment): bool => $installment->status === 'PENDENTE'), //@phpstan-ignore-line
                Tables\Actions\Action::make('refund')
                    ->translateLabel()
                    ->icon('heroicon-o-receipt-refund')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PaymentInstallments $installment) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PENDENTE',
                            'discount'       => null,
                            'surcharge'      => null,
                            'payment_date'   => null,
                            'payment_value'  => null,
                            'payment_method' => null,
                        ]);
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment refunded'))
                            ->send();
                    })->visible(fn (PaymentInstallments $installment): bool => $installment->status === 'PAGO'), //@phpstan-ignore-line
            ]);
    }
}
