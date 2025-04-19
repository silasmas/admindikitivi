<?php
namespace App\Filament\Resources;

use App\Models\Type;
use Filament\Tables;
use App\Models\Media;
use Filament\Forms\Set;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TimePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\MediaResource\Pages;
use Illuminate\Contracts\Database\Query\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon       = 'heroicon-o-film';
    protected static ?string $recordTitleAttribute = 'media_title';
    protected static ?int $navigationSort          = 1;

    public static function form(Form $form): Form
    {
        $id = '1';
        // Récupérer le nom de la route actuelle
        $currentRoute = request()->route()->getName();

        // Exemple d'utilisation pour vérifier si c'est une route d'édition
        if ($currentRoute === 'filament.admin.resources.aws.edit') {
            $id = request()->route('record');
        } else {
            $lastMedia = Media::latest()->first();
            $id        = $lastMedia ? $lastMedia->id + 1 : 1;
        }
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
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-m-play')
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
                                TextInput::make('teaser_url')
                                    ->label('Teaser URL')
                                    ->prefix('https://')
                                    ->columnSpan(6),
                                Textarea::make('media_description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),
                                Toggle::make('is_public')
                                    ->label('Active (pour le rendre visible ou pas)')
                                    ->columnSpanFull()
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->required(),
                            ])->columns(12),
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
                                    $locale     = app()->getLocale(); // Obtenir la locale actuelle
                                    $group_name = 'Type de média';   // Remplacez par votre nom de groupe

                                    return Type::whereHas('group', function ($query) use ($locale, $group_name) {
                                        $query->where('group_name->' . $locale, $group_name);
                                    })->get()->mapWithKeys(function ($type) use ($locale) {
                                        // Décodez le champ type_name
                                        $typeNames = json_decode($type->type_name, true);
                                        return [$type->id => $typeNames[$locale] ?? '']; // Utiliser une valeur par défaut si la langue n'existe pas
                                    });                                              // 'name' est le champ à afficher, 'id' est la valeur
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
                        ])->columns(12),
                    ]),
                    Step::make('Étape 3')->schema([
                        Section::make('Upload des couvertures')->schema([
                            FileUpload::make('cover_url')
                                ->label('Couverture')
                                ->directory('cover')
                            // ->disk('s3')
                            // ->directory((fn($record) => 'images/medias/' . $id)) // Spécifiez le répertoire
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
                            // ->disk('s3')
                            // ->directory((fn($record) => 'images/medias/' . $id)) // Spécifiez le répertoire
                                ->imageEditor()
                                ->getUploadedFileNameForStorageUsing(
                                    fn(TemporaryUploadedFile $file): string => (string) str($file->getClientOriginalName())
                                        ->prepend('custom-prefix-'),
                                )
                                ->imageEditorMode(2)
                                ->downloadable()
                                ->visibility('private')
                                ->image()
                                ->maxSize(3024)
                                ->columnSpan(6)
                                ->previewable(true),
                        ]),
                    ]),
                    Step::make('Étape 4')->schema([
                        // Section::make('Vidéo')->schema([
                        //     TextInput::make('media_url')
                        //         ->label('Lien de la vidéo')
                        //         ->prefix('https://')
                        //         ->columnSpan(12),
                        //     // FileUpload::make('media_url')
                        //     //     ->label('Couverture en miniature')
                        //     //     ->disk('s3')
                        //     //     ->directory((fn($record) => 'images/medias/' . $id)) // Spécifiez le répertoire
                        //     //     ->preserveFilenames() // Pour garder le nom original
                        //     //     ->visibility('private')
                        //     //     ->columnSpan(12)
                        //     //     ->maxSize(102400) // Taille maximale en Ko (100 Mo)
                        //     //     ->previewable(true),
                        // ])->columns(12),
                        Section::make('Vidéo')->schema([
                            \Filament\Forms\Components\View::make('livewire.upload-video-chunked')
                                ->columnSpan(12),
                                TextInput::make('media_url')
                                ->id('media_url_filament')
                                ->label('Lien de la vidéo')
                                ->disabled()
                                ->dehydrated(true)
                                ->afterStateHydrated(fn ($component, $state) => $component->state($state))
                                ->columnSpan(12)
                                ->helperText('Cliquez sur 👁️ pour voir la vidéo.')
                                ->suffixActions([
                                    Action::make('ouvrir')
                                        ->icon('heroicon-o-arrow-top-right-on-square')
                                        ->url(fn ($state) => $state)
                                        ->openUrlInNewTab()
                                        ->visible(fn ($state) => filled($state)),

                                    // Action::make('copier')
                                    //     ->icon('heroicon-o-clipboard-document')
                                    //     ->tooltip('Copier le lien')
                                    //     ->extraAttributes([
                                    //         'x-on:click' => new \Illuminate\Support\HtmlString(
                                    //             'navigator.clipboard.writeText(document.querySelector(\'[id^="media_url_filament"]\')?.value ?? "").then(() => window.dispatchEvent(new CustomEvent("media-url-copied")))'
                                    //         ),
                                    //     ]),

                                    Action::make('voir')
                                        ->icon('heroicon-o-eye')
                                        ->tooltip('Prévisualiser la vidéo')
                                        ->extraAttributes([
                                            'x-on:click' => new \Illuminate\Support\HtmlString(
                                                'window.dispatchEvent(new CustomEvent("preview-media-url"))'
                                            ),
                                        ]),
                                ])


                        ])->columns(12),
                    ]),

                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('cover_url')
                    ->label("Couverture")
                    ->defaultImageUrl(url('assets/images/avatars/default.jpg')),
                ImageColumn::make('thumbnail_url')
                    ->label("Miniature")
                    ->defaultImageUrl(url('assets/images/avatars/default.jpg')),
                TextColumn::make('media_title')
                    ->label('Titre')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('source')
                    ->label('Source')
                    ->searchable(),
                IconColumn::make('is_public')
                    ->label('Est active')
                    ->boolean(),
                TextColumn::make('time_length')
                    ->label('Temps du media')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('author_names')
                    ->label('Auteur')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('artist_names')
                    ->label('Artiste')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('writer')
                    ->label('Ecrit par ')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('director')
                    ->label('Réalisateur')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('published_date')
                    ->date()
                    ->since()
                    ->label('Date de publication')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                IconColumn::make('for_youth')
                    ->label('Pour enfant?')
                    ->boolean(),
                IconColumn::make('is_live')
                    ->label('Est un live?')
                    ->boolean(),
                TextColumn::make('belongs_to')
                    ->label('Type')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                // TextColumn::make('media_url')
                // ->label('Aperçu')
                // ->formatStateUsing(function ($state, $record) {
                //     $thumbnail = $record->thumbnail_url ?? url('assets/images/avatars/default.jpg');
                //     $videoUrl = $record->media_url ?? '';
                //     $source = strtolower($record->source ?? '');

                //     return view('components.video-preview', compact('thumbnail', 'videoUrl', 'source'))->render();
                // })
                // ->html()->disableClick(), // 🔥 empêche le redirect sur clic,
                TextColumn::make('media_url')
                    ->label('Action')
                    ->formatStateUsing(fn($state) => '<a href="' . $state . '" target="_blank" class="text-primary underline">🎬 Lire</a>')
                    ->html(),

                // TextColumn::make('media_url')
                //     ->label('Télécharger')
                //     ->formatStateUsing(fn($state) => '<a href="' . $state . '" download class="text-success underline">📥 Télécharger</a>')
                //     ->html(),
                // ViewColumn::make('media_url')
                //     ->label('Lire')
                //     ->view('filament.columns.video-modal')
                //     ->viewData([fn ($record) => [
                //         'recordId' => $record->id,
                //         'mediaUrl' => $record->media_url,
                //     ]]),

                TextColumn::make('created_at')
                    ->label('Date de création')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Date de mis à jour')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('Est un live')
                    ->query(fn(Builder $query) => $query->where('is_live', true)),
                Filter::make('Pour enfant')
                    ->query(fn(Builder $query) => $query->where('for_youth', true)),
                Filter::make('Source')
                    ->query(fn(Builder $query) => $query->where('source', true)),
                // DatePicker::make('Date decut')
                //     ->placeholder(fn($state) => now()->format('M d,Y')),
                SelectFilter::make('category_id')
                    ->label('Catégorie')
                    ->options(Category::select('category_name')->get()->map(function ($category) {
                        // return [$category->id => $category->category_name?? ''];
                        // Assurez-vous que category_name est bien une chaîne
                        $name = is_array($category->category_name) ? ($category->category_name['fr'] ?? '') : $category->category_name;
                        // dd([$category->id => $name]);

                        return [$category->id => $name];
                    })->toArray()),

                // SelectFilter::make('source')
                // ->label('Source')
                // ->options(
                //     Media::select('source')
                //         ->distinct() // Récupère uniquement les valeurs distinctes
                //         ->pluck('source', 'source') // Crée un tableau associatif avec 'source' comme clé et valeur
                // ),
                // Dans votre classe de ressource

            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
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
    public static function getActions(): array
    {
        return [
            Action::make('Vue en Grille')
                ->url(route('filament.admin.resources.media.gallery'))
                ->icon('heroicon-o-view-columns'),
        ];
    }
    public static function getLabel(): string
    {
        return 'Galerie';
    }

    public static function getNavigationLabel(): string
    {
        return 'Media';
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListMedia::route('/'),
            'create' => Pages\CreateMedia::route('/create'),
            'edit'   => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    public static function getNavigationBadgeColor(): string | array | null
    {
        return "info";
    }
    public function save(array $data)
    {
                                           // Supposons que vous ayez déjà récupéré l'instance de $media
        $media = Media::find($data['id']); // ou toute autre méthode pour récupérer l'enregistrement

        // Si un nouveau fichier a été téléchargé, mettez à jour l'URL
        if (isset($data['media_file_url'])) {
            $pathUrl          = $data['media_file_url']->store('videos', 's3'); // Spécifiez le répertoire
            $media->media_url = config('filesystems.disks.s3.url') . '/' . ltrim($pathUrl, '/');
        }

        // Mettez à jour d'autres champs si nécessaire
        $media->updated_at = now();

        // Sauvegardez les changements
        $media->save();
    }
    protected static function afterCreate($record)
    {
        // Logique à exécuter après la création de l'enregistrement

        // Exemple : Déplacer le fichier téléchargé vers un répertoire spécifique
        if ($record->media_url) {
            // Définir le chemin de destination
            $destinationPath = 'uploads/media/' . $record->id;

            // Déplacer le fichier vers le nouveau répertoire
            $filePath = storage_path('app/public/' . $record->media_url);
            if (file_exists($filePath)) {
                // Créer le répertoire s'il n'existe pas
                if (! file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Déplacer le fichier
                rename($filePath, $destinationPath . '/' . basename($record->media_url));
            }
        }
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['media_title', 'source', 'writer'];
    }
}
