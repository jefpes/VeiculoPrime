<?php

namespace App\Filament\Admin\Resources\VehicleResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Model;

class DocPhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'docPhotos';

    protected static ?string $label = 'Foto de documento';

    protected static ?string $title = 'Fotos de documentos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('Foto')
                    ->multiple(fn (string $operation): bool => $operation === 'create')
                    ->maxSize(10240) // 10MB
                    ->maxFiles(5)
                    ->columnSpanFull()
                    ->required()
                    ->image()
                    ->directory('vehicle_doc_photos'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('path')
            ->columns([
                Tables\Columns\ImageColumn::make('path')->size(200)->label('Foto'),
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
