<?php

namespace App\Forms\Components;

use Filament\Tables\Columns\Column;

class SvgColumn extends Column
{
    protected string $view = 'components.svg-column';

    public function svg(mixed $callback = null): static
    {
        $this->state($callback ?? fn ($record) => $record->icon);

        return $this;
    }
}
