<?php

namespace App\Forms\Components;

use Leandrocfe\FilamentPtbrFormFields\Money;

class MoneyInputBKP extends Money
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function getValue(): mixed
    {
        $sanitized = $this->sanitizeState($this->getState());
        $money     = money(amount: $sanitized, currency: $this->getCurrency());

        if ($this->getDehydrateMask()) {
            return $money->formatted();
        }

        return $this;
    }
}
/**
*
* MoneyInput::make('discount')
* ->label('Desconto')
*  ->live(debounce: 1000)
*  ->intFormat()
*  ->key('discount')
*  ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, $state) {
*   dump($state);
*   self::updateInstallmentValues($set, $get);
*  }),
*
*
*
* public static function updateInstallmentValues(Forms\Set $set, $component): void
*{
*   // Obtém os valores de entrada com valores padrão (0) quando vazio
*  $total        = $component->getContainer()->getComponent('total')->getValue() ?? 0;
 */
