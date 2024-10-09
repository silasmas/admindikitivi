<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LegalInfoContent;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LegalInfoContentResource\Pages;
use App\Filament\Resources\LegalInfoContentResource\RelationManagers;
use App\Filament\Resources\LegalInfoContentResource\Pages\EditLegalInfoContent;
use App\Filament\Resources\LegalInfoContentResource\Pages\ListLegalInfoContents;
use App\Filament\Resources\LegalInfoContentResource\Pages\CreateLegalInfoContent;

class LegalInfoContentResource extends Resource
{
    protected static ?string $model = LegalInfoContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $modelLabel = 'Apropos contenu';
    protected static ?string $navigationGroup = 'Confidentialités';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Group::make([
                    Section::make('Formulaire')->schema([
                        Select::make('legal_info_title_id')
                            ->label('Titre')
                            ->searchable()
                            ->columnSpan(12)
                            ->preload()
                            ->required()
                            ->relationship('legal_info_title', 'title'), // 'roles' est la méthode du modèle User, 'name' est l'attribut à afficher

                        TextInput::make(name: 'subtitle.fr')
                            ->label('Sous titre  (Français)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subtitle.en')
                            ->label('Sous titre (Anglais)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subtitle.ln')
                            ->label('Sous titre (Lingala)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        MarkdownEditor::make(name: 'content.fr')
                            ->label('Description  (Français)')
                            ->columnSpan(12),
                        MarkdownEditor::make('content.en')
                            ->label('Description (Anglais)')
                            ->columnSpan(12),
                        MarkdownEditor::make('content.ln')
                            ->label('Description (Lingala)')
                            ->columnSpan(12),
                    ])->columnS(12)
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'legal_info_title.title.fr')
                    ->label(label: 'Sujet')
                    // ->formatStateUsing(fn($record) => $record->role->pluck('subject_name')->unique()->join(', '))
                    ->searchable(),
                TextColumn::make('subtitle.fr')
                    ->searchable(),
                TextColumn::make('content.fr')
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListLegalInfoContents::route('/'),
            // 'create' => Pages\CreateLegalInfoContent::route('/create'),
            // 'edit' => Pages\EditLegalInfoContent::route('/{record}/edit'),
        ];
    }
}
