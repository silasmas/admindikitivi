<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make([
                    Section::make('Information générale')->schema([
                        TextInput::make('firstname')->required()
                            ->columnSpan(4)
                            ->label("Prenom"),
                        TextInput::make('lastname')->required()
                            ->columnSpan(4)
                            ->label("Nom"),
                        TextInput::make('surname')
                            ->columnSpan(4)
                            ->label("Postnom"),
                        Select::make('gender')
                            ->options([
                                'H' => 'Homme',
                                'F' => 'Femme',
                            ])
                            ->label("Sexe")
                            ->searchable()->columnSpan(4),
                        TextInput::make('phone')
                            ->columnSpan(4)
                            ->label("Telephone")
                            ->unique(User::class, 'phone', ignoreRecord: true),
                        DatePicker::make('birth_date')->label("Date d'anniversair")->columnSpan(4),
                        Select::make('country_id')
                            ->searchable()
                            ->preload()
                            ->columnSpan(6)
                            ->relationship('country', 'country_name'),
                        Select::make('status_id')
                            ->label('Status')
                            ->searchable()
                            ->preload()
                            ->columnSpan(6)
                            ->relationship('status', 'status_name'),

                    ])->columns(12),
                    Section::make('image')->schema([
                        FileUpload::make('avatar_url')
                            ->label('Proto profil')
                            ->directory('profil')
                            ->avatar()
                            ->imageEditor()
                            ->imageEditorMode(2)
                            ->circleCropper()
                            ->downloadable()
                            ->image()
                            ->maxSize(1024)
                            ->previewable(true)
                            ->imageEditorAspectRatios([
                                null,
                                '16:9',
                                '4:3',
                                '1:1',
                            ]),

                    ]),
                    Section::make('Information securité')->schema([
                        TextInput::make('email')->label("Email")
                            ->email()->maxLength(255)->unique(ignoreRecord: true)
                            ->required()->columnSpan(6)
                            ->unique(User::class, 'email', ignoreRecord: true),

                        TextInput::make('password')->password()->label("Mot de passe")
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)->columnSpan(4),

                    ])->columns(12),
                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->circular()
                    ->defaultImageUrl(url('assets/images/avatars/default.jpg')),
                TextColumn::make('firstname')
                    ->label('Prenom')->searchable(),
                TextColumn::make('lastname')
                    ->label('Nom')->searchable(),
                TextColumn::make('gender')->badge()
                    ->label('Sexe')->searchable(),
                TextColumn::make('birth_date')
                    ->label('Date de naissance')->dateTime()->sortable(),
                TextColumn::make('phone')
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->copyMessage('Phone copié')
                    ->copyMessageDuration(1500)
                    ->label('Telephone')->searchable(),
                TextColumn::make('email')->searchable()
                    ->copyable()
                    ->copyMessage('addresse Email copié')
                    ->copyMessageDuration(1500)
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('country.country_name')
                    ->label('Pays')->searchable(),
                TextColumn::make('status.status_name')
                    ->label('Status')->searchable()->badge(),
                TextColumn::make('created_at')->dateTime()->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->relationship('status', 'status_name'),
                SelectFilter::make('country')->relationship('country', 'country_name'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
