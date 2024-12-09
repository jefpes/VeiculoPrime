<?php

namespace App\Filament\Admin\Resources\VehicleResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Model;

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';

    protected static ?string $recordTitleAttribute = 'path';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('Foto')
                    ->image()
                    ->multiple(fn (string $operation): bool => $operation === 'create')
                    ->maxSize(10240) // 10MB
                    ->maxFiles(5)
                    ->directory('vehicle_photos')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Foto')
                    ->size(200),
                Tables\Columns\ToggleColumn::make('main')
                    ->label('Principal')
                    ->afterStateUpdated(function (Model $record, $state) {
                        if ($state) {
                            $record->vehicle->photos()->where('id', '!=', $record->id)->update(['main' => false]); //@phpstan-ignore-line
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth('2xl')
                    ->label('Adicionar Fotos')
                    ->modalHeading('Adicionar Fotos')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['path'] = is_array($data['path']) ? $data['path'] : [$data['path']];

                        return $data;
                    })
                    ->using(function (array $data, PhotosRelationManager $livewire): Model {
                        $model      = $livewire->getOwnerRecord();
                        $firstPhoto = $model->photos()->create([ //@phpstan-ignore-line
                            'path' => $data['path'][0],
                        ]);

                        // Create additional photos
                        foreach (array_slice($data['path'], 1) as $path) {
                            $model->photos()->create([ //@phpstan-ignore-line
                                'path' => $path,
                            ]);
                        }

                        return $firstPhoto;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
