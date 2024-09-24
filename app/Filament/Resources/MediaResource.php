<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MediaResource\Pages;
use App\Filament\Resources\MediaResource\RelationManagers;
use App\Models\Media;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type_id')
                    ->numeric(),
                Forms\Components\TextInput::make('user_id')
                    ->numeric(),
                Forms\Components\TextInput::make('media_title')
                    ->maxLength(255),
                Forms\Components\Textarea::make('media_description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
                Forms\Components\TextInput::make('belonging_count')
                    ->numeric(),
                Forms\Components\TextInput::make('time_length'),
                Forms\Components\Textarea::make('media_url')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('teaser_url')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('author_names')
                    ->maxLength(255),
                Forms\Components\TextInput::make('artist_names')
                    ->maxLength(255),
                Forms\Components\TextInput::make('writer')
                    ->maxLength(255),
                Forms\Components\TextInput::make('director')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('published_date'),
                Forms\Components\Textarea::make('cover_url')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('thumbnail_url')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\Toggle::make('for_youth')
                    ->required(),
                Forms\Components\Toggle::make('is_live')
                    ->required(),
                Forms\Components\TextInput::make('belongs_to')
                    ->numeric(),
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
}
