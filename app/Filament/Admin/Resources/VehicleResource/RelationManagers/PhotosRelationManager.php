<?php

namespace App\Filament\Admin\Resources\VehicleResource\RelationManagers;

use App\Tools\BasePhotoRelationManager;
use Filament\Tables\Table;
use Filament\{Tables};

class PhotosRelationManager extends BasePhotoRelationManager
{
    protected function getPhotoDirectory(): string
    {
        return 'vehicle_photos';
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                Tables\Columns\Layout\Grid::make()
                    ->columns(1)
                    ->schema([
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\Layout\Grid::make()
                                ->columns(1)
                                ->schema([
                                    Tables\Columns\ImageColumn::make('path')
                                        ->label('Foto')
                                        ->height(300)
                                        ->width(240)
                                        ->extraImgAttributes(['class' => 'rounded-md']),
                                ])->grow(false),
                            Tables\Columns\Layout\Grid::make()
                                ->columns(1)
                                ->schema([
                                    Tables\Columns\ToggleColumn::make('main')
                                        ->extraAttributes(['class' => 'px-4'])
                                        ->label('Principal')
                                        ->afterStateUpdated(function ($record, $state) {
                                            if ($state) {
                                                $record->vehicle->photos()->where('id', '!=', $record->id)->update(['main' => false]);
                                            }
                                        }),
                                ])->grow(false),
                        ]),
                    ])->grow(false),
            ]);
    }
}
