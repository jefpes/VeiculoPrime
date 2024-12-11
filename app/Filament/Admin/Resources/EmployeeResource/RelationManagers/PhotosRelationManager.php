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
                    ->label(fn (string $operation) => $operation === 'create' ? 'Fotos' : 'Foto')

                    ->multiple(fn (string $operation): bool => $operation === 'create')
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
                        ]),
                    ]),
            ])
            ->contentGrid([
                'md'  => 2,
                'xl'  => 3,
                '2xl' => 4,
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
                Tables\Actions\Action::make('setAsMain')
                    ->icon('heroicon-o-star')
                    ->color(fn ($record) => $record->is_main ? 'success' : 'gray')
                    ->label('Main')
                    ->hiddenLabel()
                    ->iconSize('lg')
                    ->action(function ($record) {
                        $record->photoable->photos()->update(['is_main' => false]);
                        $record->update(['is_main' => true]);
                    }),
                Tables\Actions\Action::make('setAsPublic')
                    ->icon(fn ($record) => $record->is_public ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->color(fn ($record) => $record->is_public ? 'warning' : 'gray')
                    ->label(fn ($record) => $record->is_public ? 'Public' : 'Private')
                    ->iconSize('lg')
                    ->hiddenLabel()
                    ->action(function ($record) {
                        $record->update(['is_public' => !$record->is_public]);
                    }),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->iconSize('lg')
                    ->modalWidth('2xl')
                    ->label('Editar Foto')
                    ->modalHeading('Editar Foto'),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->iconSize('lg')
                    ->modalHeading('Excluir Foto'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->modalHeading('Excluir Fotos Selecionadas')->label('Excluir Fotos Selecionadas'),
            ]);
    }
}
