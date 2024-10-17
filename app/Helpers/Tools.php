<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Number;

class Tools
{
    public static function spellMonetary(?float $value): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'valor não especificado';
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

    public static function spellNumber(?float $value): string
    {

        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'valor não especificado';
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
            return 'valor não especificado';
        }

        $value = Carbon::parse($value)->format('d/m/Y');

        return $value;
    }

    public static function spellDate(?string $value, string $locale = 'pt_BR', string $isoFormat = 'LL'): string
    {
        // Verifica se o valor é nulo e retorna uma mensagem padrão
        if (is_null($value)) {
            return 'valor não especificado';
        }

        $value = Carbon::create($value)->locale($locale)->isoFormat($isoFormat);

        return $value;
    }

}
