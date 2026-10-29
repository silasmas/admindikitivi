<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentFilterResource\Pages;
use App\Models\Category;
use App\Models\ContentFilter;
use App\Models\Type;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Filtres "Jeunesse" de l'application (Bloc 2) : Dessins animés, Histoires bibliques, Séries...
 * Le filtre "Tous" est toujours affiché en premier par l'application et n'est pas géré ici.
 */
class ContentFilterResource extends Resource
{
    protected static ?string $model = ContentFilter::class;

    protected static ?string $navigationIcon = 'heroicon-o-funnel';
    protected static ?string $navigationGroup = 'Configuration';
    protected static ?string $navigationLabel = 'Filtres Jeunesse';
    protected static ?string $modelLabel = 'filtre Jeunesse';
    protected static ?string $pluralModelLabel = 'filtres Jeunesse';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Libellé')->schema([
                    TextInput::make('filter_name.fr')
                        ->label('Nom (Français)')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),
                    TextInput::make('filter_name.en')
                        ->label('Nom (Anglais)')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),
                    TextInput::make('filter_name.ln')
                        ->label('Nom (Lingala)')
                        ->required()
                        ->maxLength(255)
                        ->columnSpan(4),
                    TextInput::make('filter_key')
                        ->label('Clé technique')
                        ->helperText('Identifiant stable utilisé par l\'application (ex. : bible, cartoons).')
                        ->required()
                        ->alphaDash()
                        ->maxLength(40)
                        ->unique(ignoreRecord: true)
                        ->columnSpan(4),
                    TextInput::make('position')
                        ->label('Ordre d\'affichage')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(4),
                    Toggle::make('is_active')
                        ->label('Actif')
                        ->default(true)
                        ->inline(false)
                        ->onColor('success')
                        ->columnSpan(4),
                ])->columns(12),
                Section::make('Contenus correspondants')
                    ->description('Un média apparaît dans ce filtre s\'il a l\'un de ces types OU l\'une de ces catégories.')
                    ->schema([
                        Select::make('type_ids')
                            ->label('Types de média')
                            ->multiple()
                            ->options(fn () => Type::all()->mapWithKeys(fn ($type) => [$type->id => (string) $type->display_name])->toArray())
                            ->columnSpan(6),
                        Select::make('category_ids')
                            ->label('Catégories')
                            ->multiple()
                            ->options(fn () => Category::all()->mapWithKeys(fn ($category) => [$category->id => (string) $category->display_name])->toArray())
                            ->columnSpan(6),
                    ])->columns(12),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('position')
                    ->label('Ordre')
                    ->sortable(),
                TextColumn::make('display_name')
                    ->label('Nom'),
                TextColumn::make('filter_key')
                    ->label('Clé')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('type_ids')
                    ->label('Types')
                    ->formatStateUsing(fn ($record) => Type::whereIn('id', (array) $record->type_ids)->get()->map(fn ($type) => $type->display_name)->join(', '))
                    ->placeholder('—'),
                TextColumn::make('category_ids')
                    ->label('Catégories')
                    ->formatStateUsing(fn ($record) => Category::whereIn('id', (array) $record->category_ids)->get()->map(fn ($category) => $category->display_name)->join(', '))
                    ->limit(60)
                    ->placeholder('—'),
                IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean(),
            ])
            ->defaultSort('position')
            ->reorderable('position')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContentFilters::route('/'),
            'create' => Pages\CreateContentFilter::route('/create'),
            'edit' => Pages\EditContentFilter::route('/{record}/edit'),
        ];
    }
}
