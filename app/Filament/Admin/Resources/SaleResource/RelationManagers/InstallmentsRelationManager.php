<?php

namespace App\Filament\Admin\Resources\SaleResource\RelationManagers;

use App\Enums\PaymentMethod;
use App\Models\{PaymentInstallment};
use App\Tools\Contracts;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Support\Facades\Auth;
use Leandrocfe\FilamentPtbrFormFields\Money;
use PhpOffice\PhpWord\TemplateProcessor;

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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (string $state, $record): string|array {
                        // Se o status for 'PENDENTE', verificar se há parcelas em atraso
                        if ($state === 'PENDENTE') {
                            if ($record->due_date < now() && $record->status === 'PENDENTE') {
                                return Color::hex('#b600ff');
                            }

                            // Caso não haja parcelas em atraso, manter a cor 'info'
                            return 'info';
                        }

                        // Verificar os demais estados
                        return match ($state) {
                            'REEMBOLSADO' => 'warning',
                            'CANCELADO'   => 'danger',
                            default       => 'success',
                        };
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
                    ->authorize('receive')
                    ->translateLabel()
                    ->icon('heroicon-o-banknotes')
                    ->slideOver()
                    ->requiresConfirmation()
                    ->modalHeading(__('Receive installment'))
                    ->modalDescription(null)
                    ->modalIcon('heroicon-o-banknotes')
                    ->fillForm(fn (PaymentInstallment $record): array => [
                        'value'        => $record->value,
                        'payment_date' => now(),
                    ])
                    ->form([
                        Money::make('value')->readOnly(),
                        Forms\Components\Select::make('payment_method')
                            ->options(PaymentMethod::class)
                            ->required(),
                        Money::make('payment_value')->required(),
                        Forms\Components\DatePicker::make('payment_date')->required(),
                    ])->action(function (PaymentInstallment $installment, array $data) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PAGO',
                            'discount'       => $data['payment_value'] < $installment->value ? ($installment->value - $data['payment_value']) : 0,
                            'surcharge'      => $data['payment_value'] > $installment->value ? ($data['payment_value'] - $installment->value) : 0,
                            'payment_date'   => $data['payment_date'],
                            'payment_value'  => $data['payment_value'],
                            'payment_method' => $data['payment_method'],
                        ]);

                        if ($installment->sale->paymentInstallments->where('status', 'PENDENTE')->isEmpty()) { //@phpstan-ignore-line
                            $installment->sale->update(['status' => 'PAGO']);
                        }

                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment received'))
                            ->send();
                    }),
                Tables\Actions\Action::make('refund')
                    ->authorize('refund')
                    ->translateLabel()
                    ->icon('heroicon-o-receipt-refund')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (PaymentInstallment $installment) {
                        $installment->update([
                            'user_id'        => Auth::id(),
                            'status'         => 'PENDENTE',
                            'discount'       => null,
                            'surcharge'      => null,
                            'payment_date'   => null,
                            'payment_value'  => null,
                            'payment_method' => null,
                        ]);

                        if ($installment->sale->status === 'PAGO') {
                            $installment->sale->update(['status' => 'PENDENTE']);
                        }
                    })->after(function () {
                        Notification::make()
                            ->success()
                            ->title(__('Installment refunded'))
                            ->send();
                    }),
                Tables\Actions\Action::make('receipt')
                    ->authorize('receipt')
                    ->requiresConfirmation()
                    ->modalHeading(__('Receipt'))
                    ->label('Receipt')
                    ->translateLabel()
                    ->icon('heroicon-o-document')
                    ->iconSize('md')
                    ->color('info')
                    ->form([
                        FileUpload::make('receipt')
                            ->label('Contract')
                            ->panelAspectRatio('2:1')
                            ->storeFiles(false)
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]),
                    ])
                    ->action(function (array $data, PaymentInstallment $installment) {

                        $template = new TemplateProcessor($data['receipt']->getRealPath());

                        $caminho = Contracts::generateReceiptContract($template, $installment);

                        return response()->download($caminho)->deleteFileAfterSend(true);
                    }),
            ]);
    }
}
