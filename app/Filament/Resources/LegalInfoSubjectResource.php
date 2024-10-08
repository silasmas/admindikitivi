<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\LegalInfoSubject;
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
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LegalInfoSubjectResource\Pages;
use App\Filament\Resources\LegalInfoSubjectResource\RelationManagers;
use App\Filament\Resources\LegalInfoSubjectResource\Pages\EditLegalInfoSubject;
use App\Filament\Resources\LegalInfoSubjectResource\Pages\ListLegalInfoSubjects;
use App\Filament\Resources\LegalInfoSubjectResource\Pages\CreateLegalInfoSubject;

class LegalInfoSubjectResource extends Resource
{
    protected static ?string $model = LegalInfoSubject::class;

    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $modelLabel = 'Apropos Sujet';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Formulaire')->schema([
                        TextInput::make(name: 'subject_name.fr')
                            ->label('Nom  (Français)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subject_name.en')
                            ->label('Nom (Anglais)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subject_name.ln')
                            ->label('Nom (Lingala)')
                            ->columnSpan(4)
                            ->required()
                            ->maxLength(255),
                        Textarea::make(name: 'subject_description.fr')
                            ->label('Description  (Français)')
                            ->columnSpan(4)
                            ->maxLength(255),
                        Textarea::make('subject_description.en')
                            ->label('Description (Anglais)')
                            ->columnSpan(4)
                            ->maxLength(255),
                        Textarea::make('subject_description.ln')
                            ->label('Description (Lingala)')
                            ->columnSpan(4)
                            ->maxLength(255),
                    ])->columnS(12)
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject_name.fr')
                    ->searchable(),
                TextColumn::make('subject_description.fr')
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
            'index' => Pages\ListLegalInfoSubjects::route('/'),
            // 'create' => Pages\CreateLegalInfoSubject::route('/create'),
            // 'edit' => Pages\EditLegalInfoSubject::route('/{record}/edit'),
        ];
    }
}
