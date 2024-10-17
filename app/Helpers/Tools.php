<?php

namespace App\Helpers;

use Illuminate\Support\Number;

class Tools
{
    public static function monetarySpell(?float $value): string
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

}
