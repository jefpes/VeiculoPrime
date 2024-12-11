<?php

namespace App\Filament\Admin\Resources\EmployeeResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\{Forms, Tables};
use Illuminate\Database\Eloquent\Model;

class PhotosRelationManager extends RelationManager
{
    protected static string $relationship = 'photos';

    protected static ?string $title = 'Fotos';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('path')
                    ->label('Foto')
                    ->multiple()
                    ->maxSize(10240) // 10MB
                    ->maxFiles(5)
                    ->columnSpanFull()
                    ->required()
                    ->directory($this->getOwnerRecord()->getPhotoDirectory()) //@phpstan-ignore-line
                    ->image(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordAction(null)
            ->recordUrl(null)
            ->columns([
                Tables\Columns\ImageColumn::make('path')
                    ->label('Foto')
                    ->square()
                    ->extraImgAttributes(['class' => 'object-fill']),
                Tables\Columns\IconColumn::make('is_main')
                    ->label('Principal')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_public')
                    ->label('Pública')
                    ->boolean(),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['path'] = is_array($data['path']) ? $data['path'] : [$data['path']];

                        return $data;
                    })
                    ->using(function (array $data, $livewire): Model {
                        $model      = $livewire->getOwnerRecord();
                        $firstPhoto = $model->{static::$relationship}()->create([
                            'path' => $data['path'][0],
                        ]);

                        foreach (array_slice($data['path'], 1) as $path) {
                            $model->{static::$relationship}()->create([
                                'path' => $path,
                            ]);
                        }

                        return $firstPhoto;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('setAsMain')
                    ->label('Main')
                    ->action(function ($record) {
                        $record->photoable->photos()->update(['is_main' => false]);
                        $record->update(['is_main' => true]);
                    })
                    ->hidden(fn ($record) => $record->is_main),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
