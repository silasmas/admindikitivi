<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LegalInfoTitle;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LegalInfoTitleResource\Pages;
use App\Filament\Resources\LegalInfoTitleResource\RelationManagers;
use App\Filament\Resources\LegalInfoTitleResource\Pages\EditLegalInfoTitle;
use App\Filament\Resources\LegalInfoTitleResource\Pages\ListLegalInfoTitles;
use App\Filament\Resources\LegalInfoTitleResource\Pages\CreateLegalInfoTitle;

class LegalInfoTitleResource extends Resource
{
    protected static ?string $model = LegalInfoTitle::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $modelLabel = 'Apropos Titre';

    protected static ?string $navigationGroup = 'Confidentialités';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Formulaire')->schema([
                        Select::make('legal_info_subject_id')
                            ->label('Sujet')
                            ->searchable()
                            ->columnSpan(12)
                            ->preload()
                            ->required()
                            ->relationship('legal_info_subject', 'subject_name'), // 'roles' est la méthode du modèle User, 'name' est l'attribut à afficher

                        TextInput::make('title.fr')
                            ->label('Titre  (Français)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title.en')
                            ->label('Titre (Anglais)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title.ln')
                            ->label('Titre (Lingala)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                    ])->columnS(12)
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make(name: 'legal_info_subject.subject_name.fr')
                    ->label(label: 'Sujet')
                    // ->formatStateUsing(fn($record) => $record->role->pluck('subject_name')->unique()->join(', '))
                    ->searchable(),
                TextColumn::make('title.fr')
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
            'index' => Pages\ListLegalInfoTitles::route('/'),
            // 'create' => Pages\CreateLegalInfoTitle::route('/create'),
            // 'edit' => Pages\EditLegalInfoTitle::route('/{record}/edit'),
        ];
    }
}
