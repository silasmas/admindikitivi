<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Type;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TypeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TypeResource\RelationManagers;

class TypeResource extends Resource
{
    protected static ?string $model = Type::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('type_name')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Textarea::make('type_description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Select::make('group_id')
                    ->relationship('group', 'group_name')
                    ->searchable()
                    ->preload()
                    ->getOptionLabelUsing(function ($record) {
                        // Déterminez la langue actuelle (par exemple, en utilisant la locale de l'application)
                        $locale = app()->getLocale(); //ou utilisez une autre méthode pour obtenir la langue souhaitée
                        return $record->group_name[$locale] ?? 'Nom non disponible'; // Retourne le nom dans la langue spécifiée
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type_name')
                    ->searchable()
                    ->label('Nom')
                    ->sortable(),
                TextColumn::make('type_description')
                    ->label('Description')
                    ->sortable(),
                TextColumn::make('group.group_name')
                    ->label('Nom du groupe')
                    ->sortable(),
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
            'index' => Pages\ListTypes::route('/'),
            // 'create' => Pages\CreateType::route('/create'),
            // 'edit' => Pages\EditType::route('/{record}/edit'),
        ];
    }
}
