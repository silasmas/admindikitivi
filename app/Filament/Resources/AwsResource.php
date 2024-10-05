<?php

namespace App\Filament\Resources;

use App\Models\aws;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\AwsResource\Pages;
use App\Filament\Columns\VideoColumn;



class AwsResource extends Resource
{
    protected static ?string $model = aws::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
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
                        ->label('video')
                        ->disk('s3')
                        ->acceptedFileTypes(['video/mp4', 'video/x-msvideo', 'video/x-matroska']) // Types de fichiers acceptés
                        ->directory(fn($record) => 'images/medias/' . $record->id) // Spécifiez le répertoire
                        ->preserveFilenames() // Pour garder le nom original
                        ->visibility('public')
                        ->maxSize(51200) // Max 50 Mo
                        ->columnSpan(12)
                        ->previewable(true),
                ])
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(query: aws::query()) // Remplacez YourModel par votre modèle
            ->columns([
                TextColumn::make('nom')
                    ->searchable(),
                ImageColumn::make('image')
                    ->searchable(),
                ImageColumn::make('video')
                    ->searchable(),
                // VideoColumn::make('video')
                //     ->label('Video')
                //     ->videoUrl(fn($record) => $record->video), // Remplacez par le champ approprié
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
    public static function create(array $data): aws
    {
        dd($data);
        // Enregistrez le modèle avec le chemin du fichier S3
        $model = aws::create([
            'file_path' => $data['file']->store('uploads', 's3'), // Enregistrez le fichier dans S3
            // Autres champs à enregistrer...
        ]);

        return $model;
    }
    public function save(array $data, ?aws $record = null): aws
    {
        dd($data);
        // Validation des données
        $validator = Validator::make($data, [
            'image' => 'required|image|max:3024', // Validation de l'image
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:10240', // Limite de taille pour la vidéo
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Récupération de l'instance
        $media = Aws::find($data['id']);

        if (!$media) {
            return response()->json(['error' => 'Media not found'], 404);
        }

        // Traitement de l'image
        if (isset($data['image'])) {
            $media->image = $data['image']->store('aws_cover', 's3'); // Stockage sur S3
        }

        // Traitement de la vidéo
        if (isset($data['video'])) {
            $media->video = $data['video']->store('images/medias/' . $media->id, 's3');
        }

        // Enregistrement des modifications
        $media->save();

        return response()->json(['success' => 'Media updated successfully'], 200);
    }
}
