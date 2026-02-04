<?php

namespace App\Filament\Resources;

use App\Models\Aws;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Group;
use App\Filament\Columns\VideoColumn;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\AwsResource\Pages;



class AwsResource extends Resource
{
    protected static ?string $model = Aws::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Aws Test';
    protected static bool $shouldRegisterNavigation = false;
    public static function form(Form $form): Form
    {
        $id = '1';
        // Récupérer le nom de la route actuelle
        $currentRoute = request()->route()->getName();

        // Exemple d'utilisation pour vérifier si c'est une route d'édition
        if ($currentRoute === 'filament.admin.resources.aws.edit') {
            $id = request()->route('record');
        } else {
            $lastMedia = Aws::latest()->first();
            $id = $lastMedia ? $lastMedia->id + 1 : 1;
        }
        return $form->schema([
            Group::make([
                Section::make('Info sur les commandes')->schema([
                    TextInput::make('nom')
                        ->label('Titre du media')
                        ->columnSpan(12)
                        ->required()
                        ->maxLength(255),
                    FileUpload::make('image')
                        ->label('Couverture')
                        ->directory(directory: 'aws_cover')
                        ->imageEditor()
                        ->imageEditorMode(2)
                        ->downloadable()
                        ->visibility('private')
                        ->image()
                        ->maxSize(3024)
                        ->columnSpan(12)
                        ->previewable(true),
                    FileUpload::make('video')
                        ->label('Video' . $id)
                        ->disk('s3')
                        ->acceptedFileTypes(['video/mp4', 'video/x-msvideo', 'video/x-matroska']) // Types de fichiers acceptés
                        ->directory('images/medias/TestAwsSilas/' . $id) // Spécifiez le répertoire
                        // ->preserveFilenames() // Pour garder le nom original
                        ->visibility('public')
                        // ->maxSize(102400) // Taille maximale en Ko (100 Mo)
                        ->rules('file|max:1024000') // Taille maximale en Ko (1 Go)
                        ->columnSpan(12)
                        ->previewable(true),
                ])->columns(12),
                Section::make('Vidéo')->schema([
                    \Filament\Forms\Components\View::make('livewire.upload-video-chunked')
                        ->columnSpan(12),
                        ])->columns(12),
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(query: Aws::query()) // Remplacez YourModel par votre modèle
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                ImageColumn::make('image')
                    ->searchable(),
                ImageColumn::make('video')
                    ->searchable(),
                TextColumn::make('video')
                    ->label('Video')
                    ->formatStateUsing(fn($state) => '<video width="320" height="240" controls><source src="' . Storage::disk('s3')->url($state) . '" type="video/mp4">Your browser does not support the video tag.</video>')
                    ->html(), // Permet d'afficher du HTML
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
            'index' => Pages\ListAws::route('/'),
            'create' => Pages\CreateAws::route('/create'),
            'edit' => Pages\EditAws::route('/{record}/edit'),
        ];
    }
}
