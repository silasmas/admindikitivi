<?php

namespace App\Filament\Resources;


use Filament\Forms;
use Filament\Tables;
use App\Models\Group;
use App\Models\Status;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Concerns\Translatable;
use App\Filament\Resources\StatusResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StatusResource\RelationManagers;

class StatusResource extends Resource
{
    use  Translatable;
    protected static ?string $model = Status::class;
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('group_id')
                    ->relationship('group', 'group_name')
                    ->searchable()
                    ->options(Group::all()->pluck('group_name.ln', 'id'))
                    ->preload()
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status_name.fr')
                    ->maxLength(65535)
                    ->columnSpan(4)
                    ->label('Nom (Français)'),
                TextInput::make('status_name.en')
                    ->columnSpan(4)
                    ->label('Nom (Anglais)'),
                TextInput::make('status_name.ln')
                    ->columnSpan(4)
                    ->label('Nom (Lingala)'),
                TextInput::make('icon')
                    ->columnSpan(6)
                    ->maxLength(45),
                TextInput::make('color')
                    ->columnSpan(6)
                    ->maxLength(45),

                Textarea::make('status_description')
                    ->columnSpanFull(),
            ])->columnS(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status_name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('group.group_name')
                    ->label('Groupe')
                    ->searchable(),
                TextColumn::make('status_description')
                    ->label('Description')
                    ->searchable(),
                TextColumn::make('icon')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                TextColumn::make('color')
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListStatuses::route('/'),
            'create' => Pages\CreateStatus::route('/create'),
            'edit' => Pages\EditStatus::route('/{record}/edit'),
        ];
    }
}
