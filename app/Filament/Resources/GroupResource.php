<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Group;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\GroupResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GroupResource\Pages\EditGroup;
use App\Filament\Resources\GroupResource\Pages\ListGroups;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Filament\Resources\GroupResource\Pages\CreateGroup;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('group_name.fr')
                    ->required()
                    ->label("Nom (Français)")
                    ->columnSpan(4)
                    ->maxLength(255),
                TextInput::make('group_name.en')
                    ->required()
                    ->label("Nom (Anglais)")
                    ->columnSpan(4)
                    ->maxLength(255),
                TextInput::make('group_name.ln')
                    ->label("Nom (Lingala)")
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(255),
                Textarea::make('group_description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group_name')
                    ->searchable(),
                TextColumn::make('group_description')
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
            'index' => Pages\ListGroups::route('/'),
            // 'create' => Pages\CreateGroup::route('/create'),
            // 'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
