<?php

namespace App\Filament\Resources;

use App\Models\Type;
use Filament\Tables;
use App\Models\Media;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\MediaResource\Pages;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    public static function form(Form $form): Form
    {
        // Récupérer les catégories
        $categories = Category::all();

        // Vérifier si des catégories existent
        if ($categories->isEmpty()) {
            dd('Aucune catégorie trouvée.');
        }
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Étape 1')
                        ->schema([
                            Section::make('Information générale')->schema([
                                TextInput::make('media_title')
                                    ->label('Titre du media')
                                    ->columnSpan(4)
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('belonging_count')
                                    ->label('Nombre des contenants')
                                    ->columnSpan(4)
                                    ->numeric()
                                    ->maxLength(255),
                                TextInput::make('source')
                                    ->label('Source')
                                    ->columnSpan(4)
                                    ->required()
                                    ->maxLength(255),
                                TimePicker::make('time_length')
                                    ->label('Temps du media')
                                    ->columnSpan(4),
                                TextInput::make('author_names')
                                    ->label('Auteur')
                                    ->columnSpan(4)
                                    ->maxLength(255),
                                TextInput::make(name: 'director')
                                    ->label('Réalisateur')
                                    ->columnSpan(4)
                                    ->maxLength(255),
                                TextInput::make('writer')
                                    ->label('Ecrit par :')
                                    ->columnSpan(6)
                                    ->maxLength(255),
                                TextInput::make('artist_names')
                                    ->label('Nom de l\'artiste')
                                    ->columnSpan(6)
                                    ->maxLength(255),
                                TextInput::make('media_url')
                                    ->label('Media URL')
                                    ->prefix('https://')
                                    ->columnSpan(6),
                                TextInput::make('teaser_url')
                                    ->label('Teaser URL')
                                    ->prefix('https://')
                                    ->columnSpan(6),
                                Textarea::make('media_description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                            ])->columns(12)
                        ]),
                    Step::make('Étape 2')->schema([
                        Section::make('Information générale')->schema([
                            Select::make('for_youth')
                                ->options([
                                    '1' => 'OUI',
                                    '0' => 'NON',
                                ])
                                ->label("Pour enfant ?")
                                ->searchable()->columnSpan(6),
                            Select::make('is_live')
                                ->options([
                                    '1' => 'OUI',
                                    '0' => 'NON',
                                ])
                                ->label("Est un live?")
                                ->searchable()->columnSpan(6),
                            Select::make('belongs_to')
                                ->label('Appartien à :')
                                ->searchable()
                                ->preload()
                                ->relationship('type', 'type_name')
                                ->options(function (callable $get) {
                                    $locale = app()->getLocale(); // Obtenir la locale actuelle
                                    $group_name = 'Type de média'; // Remplacez par votre nom de groupe

                                    return Type::whereHas('group', function ($query) use ($locale, $group_name) {
                                        $query->where('group_name->' . $locale, $group_name);
                                    })->get()->mapWithKeys(function ($type) use ($locale) {
                                        // Décodez le champ type_name
                                        $typeNames = json_decode($type->type_name, true);
                                        return [$type->id => $typeNames[$locale] ?? '']; // Utiliser une valeur par défaut si la langue n'existe pas
                                    }); // 'name' est le champ à afficher, 'id' est la valeur
                                })
                                ->columnSpan(12),
                            CheckboxList::make('category_id') // Utilisation de CheckboxList
                                ->label('Choisissez au moins une catégorie')
                                ->searchable()
                                ->columns(6)
                                ->relationship('categories', 'category_name')
                                ->options($categories->mapWithKeys(function ($category) {
                                    if (is_null($category)) {
                                        dd('La catégorie est nulle.');
                                    }
                                    return [$category->id => $category->category_name];
                                }))
                                ->required(),
                        ])->columns(12)
                    ]),
                    Step::make('Étape 3')->schema([
                        Section::make('Upload des couvertures')->schema([
                            FileUpload::make('cover_url')
                                ->label('Couverture')
                                ->directory('cover')
                                ->imageEditor()
                                ->imageEditorMode(2)
                                ->downloadable()
                                ->visibility('private')
                                ->image()
                                ->maxSize(3024)
                                ->columnSpan(6)
                                ->previewable(true),
                            FileUpload::make('thumbnail_url')
                                ->label('Couverture en miniature')
                                ->directory('thumbnail')
                                ->imageEditor()
                                ->imageEditorMode(2)
                                ->downloadable()
                                ->visibility('private')
                                ->image()
                                ->maxSize(3024)
                                ->columnSpan(6)
                                ->previewable(true),
                        ])
                    ]),
                    Step::make('Étape 4')->schema([
                        Section::make('Vidéo')->schema([
                            FileUpload::make('thumbnail_url')
                                ->label('Couverture en miniature')
                                ->disk('s3')
                                ->directory('form-attachments')
                                ->visibility('private')
                                ->columnSpan(12)
                                ->previewable(true),
                        ])->columns(12)
                    ]),

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('media_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('belonging_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time_length'),
                Tables\Columns\TextColumn::make('author_names')
                    ->searchable(),
                Tables\Columns\TextColumn::make('artist_names')
                    ->searchable(),
                Tables\Columns\TextColumn::make('writer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('director')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('for_youth')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_live')
                    ->boolean(),
                Tables\Columns\TextColumn::make('belongs_to')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? "danger" : "success";
    }
}
