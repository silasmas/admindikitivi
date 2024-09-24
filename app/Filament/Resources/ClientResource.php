<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClientResource\RelationManagers;

class ClientResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function getLabel(): string
    {
        return 'Client';
    }

    public static function getPluralLabel(): string
    {
        return 'Clients';
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->query(User::whereHas('roles', function ($query) {
            $query->where('role_name', 'Membre'); // Assurez-vous que 'name' correspond à votre colonne de rôle
        }))->columns([
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
