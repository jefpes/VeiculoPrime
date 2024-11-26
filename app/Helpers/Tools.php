<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Number;

class Tools
{
    /**
     * Calcula o valor da parcela com juros compostos.
     *
     * @param float|null $principal Valor principal (sem juros)
     * @param float $rate Taxa de juros em porcentagem
     * @param int $months Número de parcelas
     * @return array<string, float> Um array com 'total' e 'installment' com valores numéricos (float)
     */
    public static function calculateCompoundInterest(?float $principal, float $rate, int $months): array
    {
        if ($principal === null || $months <= 0) {
            return ['total' => 0.0, 'installment' => 0.0];
        }

        // Caso a taxa de juros seja 0%, retorna parcelas sem juros
        if ($rate == 0 || $rate == null) {
            $installment = $principal / $months;
            $total       = $principal;
        } else {
            $rate = $rate / 100; // Converte a taxa de juros para decimal
            // Cálculo do valor da parcela com juros compostos
            $installment = ($principal * $rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
            $total       = $installment * $months;
        }

        return [
            'total'       => round($total, 2),           // Valor total pago com juros (ou sem, caso a taxa seja 0)
            'installment' => round($installment, 2), // Valor da parcela
        ];
    }

    public static function spellMonetary(?float $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        // Separa o valor em reais e centavos
        $reais    = floor($value); // Parte inteira
        $centavos = round(($value - $reais) * 100); // Parte decimal

        // Converte a parte inteira (reais) para extenso
        $reaisExtenso = Number::spell($reais, locale: 'br');

        // Definir plural ou singular para reais e centavos
        $reaisText    = $reais == 1 ? 'real' : 'reais';
        $centavosText = $centavos == 1 ? 'centavo' : 'centavos';

        // Se não houver centavos
        if ($centavos == 0) {
            return "$reaisExtenso $reaisText";
        }

        // Se não houver reais
        if ($reais == 0) {
            $centavosExtenso = Number::spell($centavos, locale: 'br');

            return "$centavosExtenso $centavosText";
        }

        // Converte a parte decimal (centavos) para extenso
        $centavosExtenso = Number::spell($centavos, locale: 'br');

        // Retorna o valor completo em extenso
        return "$reaisExtenso $reaisText e $centavosExtenso $centavosText";
    }

    public static function spellPercentage(?float $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Porcentagem não especificada';
        }

        // Verifica se o número é inteiro (não possui parte decimal)
        if (floor($value) == $value) {
            // Converte a parte inteira para texto em português
            $parteInteiraTexto = Number::spell((int)$value, locale: 'br');

            // Retorna o valor com "por cento" para inteiros
            return "$parteInteiraTexto por cento";
        }

        // Caso contrário, separa a parte inteira e a parte decimal
        $parteInteira = floor($value); // Parte inteira do valor
        $parteDecimal = round(($value - $parteInteira) * 100); // Parte decimal, máximo de duas casas

        // Converte a parte inteira para texto em português
        $parteInteiraTexto = Number::spell($parteInteira, locale: 'br');

        // Parte decimal como texto, com dígitos separados (ex: 3 -> "zero três")
        $parteDecimalTexto = str_split(str_pad((string)$parteDecimal, 2, '0', STR_PAD_LEFT)); // Converte para string antes de usar str_pad
        $parteDecimalTexto = implode(' ', array_map(fn ($d) => Number::spell((int) $d, locale: 'br'), $parteDecimalTexto));

        // Combina a parte inteira e decimal com a palavra "porcento"
        return "$parteInteiraTexto vírgula $parteDecimalTexto porcento";
    }

    public static function spellNumber(?float $value): string
    {

        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        // Separa a parte inteira e a parte decimal
        $parteInteira    = floor($value); // Parte inteira
        $parteFracionada = $value - $parteInteira; // Parte fracionária

        // Converte a parte inteira para extenso
        $parteInteiraExtenso = Number::spell($parteInteira, locale: 'br');

        // Caso não haja parte fracionada, retorna apenas a parte inteira
        if ($parteFracionada == 0) {
            return $parteInteiraExtenso;
        }

        // Limita a parte fracionada a dois dígitos, multiplicando por 100
        $parteFracionada = round($parteFracionada * 100);

        // Verifica se a parte fracionada é maior que 0 e trata a exibição
        if ($parteFracionada > 0) {
            $parteFracionadaExtenso = Number::spell($parteFracionada, locale: 'br');

            return "$parteInteiraExtenso vírgula $parteFracionadaExtenso";
        }

        return $parteInteiraExtenso;
    }

    public static function dateFormat(?string $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        $value = Carbon::parse($value)->format('d/m/Y');

        return $value;
    }

    public static function spellDate(?string $value, string $locale = 'pt_BR', string $isoFormat = 'LL'): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'Valor não especificado';
        }

        $value = Carbon::create($value)->locale($locale)->isoFormat($isoFormat);

        return $value;
    }

}
