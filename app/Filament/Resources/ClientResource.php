<?php

namespace App\Filament\Resources;


use App\Models\Country;
use App\Models\User;
use App\Models\Role;
use App\Models\Status;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
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
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ClientResource\Pages;

class ClientResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $recordTitleAttribute = 'firstname';
    protected static ?int $navigationSort = 2;
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
        // return $form
        //     ->schema([
        //         //
        //     ]);
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Étape 1')
                        ->schema([
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
                                    ->relationship('country', 'id')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        if (!$record) {
                                            return '—';
                                        }
                                        $label = filled($record->country_name) ? $record->country_name : (string) $record->id;
                                        return (string) ($label ?? '—');
                                    }),
                                Select::make('status_id')
                                    ->label('Status')
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(6)
                                    ->relationship('status', 'id')
                                    ->getOptionLabelFromRecordUsing(function (?Status $record) {
                                        if (!$record) {
                                            return '—';
                                        }
                                        $label = $record->getStatus_name(app()->getLocale()) ?? $record->getStatus_name('fr') ?? (string) $record->id;
                                        return (string) ($label ?? '—');
                                    }),

                            ])->columns(12)
                        ]),
                    Step::make('Étape 2')
                        ->schema([
                            Section::make('Information générale')->schema([
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
                                    ->columnSpan(6)
                                    ->previewable(true),

                                Select::make('roles')
                                    ->label('Rôles')
                                    ->columnSpan(6)
                                    ->searchable()
                                    ->preload()
                                    ->multiple()
                                    ->required()
                                    ->relationship('roles', 'id')
                                    ->getOptionLabelFromRecordUsing(function ($record) {
                                        if (!$record) {
                                            return '—';
                                        }
                                        $label = $record->role_name ?? $record->name ?? (string) $record->id;
                                        return (string) ($label ?? '—');
                                    }),
                            ])->columns(12)
                        ]),
                    Step::make('Étape 3')
                        ->schema([
                            Section::make('Information générale')
                                ->schema([
                                    TextInput::make('email')->label("Email")
                                        ->email()->maxLength(255)->unique(ignoreRecord: true)
                                        ->required()->columnSpan(6)
                                        ->unique(User::class, 'email', ignoreRecord: true),

                                    TextInput::make('password')->password()->label("Mot de passe")
                                        ->dehydrated(fn($state) => filled($state))
                                        ->required(fn(Page $livewire) => $livewire instanceof CreateRecord)->columnSpan(4),

                                ])->columns(12)
                        ]),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->query(User::whereHas('roles', function ($query) {
            $query->where(function ($q) {
                $q->where('role_name', 'Membre')->orWhere('name', 'Membre');
            });
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
                ->label('Date de naissance')->sortable(),
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
            TextColumn::make('status_id')
                ->label('Status')
                ->formatStateUsing(fn ($state, $record) => $record->status?->getStatus_name(app()->getLocale()) ?? $record->status?->getStatus_name('fr'))
                ->searchable()
                ->badge(),
            TextColumn::make('roles')
                ->label('Rôles')
                ->formatStateUsing(fn ($state, $record) => $record->roles->map(fn ($r) => $r->role_name ?? $r->name)->filter()->implode(', ') ?: '—')
                ->badge()
                ->color('success'),
            TextColumn::make('updated_at')
                ->label(label: 'Modifier')
                ->since()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('created_at')
                ->label('Création')
                ->since()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
            ->filters([
                SelectFilter::make('age_range')
                    ->label('Tranche d’âge')
                    ->options([
                        'minor' => 'Moins de 18 ans',
                        '18-24' => '18-24 ans',
                        '25-34' => '25-34 ans',
                        '35-44' => '35-44 ans',
                        '45-54' => '45-54 ans',
                        '55+'   => '55 ans et plus',
                    ])
                    ->query(function (Builder $query, array $data): void {
                        $value = $data['age_range'] ?? null;
                        if (blank($value)) {
                            return;
                        }
                        $today = now()->toDateString();
                        $query->whereNotNull('birth_date')->where(function (Builder $q) use ($value): void {
                            match ($value) {
                                'minor' => $q->where('birth_date', '>=', now()->subYears(18)->toDateString()),
                                '18-24' => $q->whereBetween('birth_date', [now()->subYears(25)->toDateString(), now()->subYears(18)->toDateString()]),
                                '25-34' => $q->whereBetween('birth_date', [now()->subYears(35)->toDateString(), now()->subYears(25)->toDateString()]),
                                '35-44' => $q->whereBetween('birth_date', [now()->subYears(45)->toDateString(), now()->subYears(35)->toDateString()]),
                                '45-54' => $q->whereBetween('birth_date', [now()->subYears(55)->toDateString(), now()->subYears(45)->toDateString()]),
                                '55+'   => $q->where('birth_date', '<=', now()->subYears(55)->toDateString()),
                                default => null,
                            };
                        });
                    }),
                SelectFilter::make('status_id')
                    ->label('Status')
                    ->options(fn () => Status::all()->mapWithKeys(fn (Status $s) => [$s->id => (string) ($s->getStatus_name(app()->getLocale()) ?? $s->getStatus_name('fr') ?? $s->id ?? '—')])),
                SelectFilter::make('country_id')
                    ->label('Pays')
                    ->options(fn () => Country::all()->mapWithKeys(fn (Country $c) => [$c->id => (string) ($c->country_name ?? $c->id ?? '—')])),
                SelectFilter::make('roles')
                    ->label('Rôle')
                    ->relationship('roles', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Role $r) => (string) ($r->role_name ?? $r->name ?? $r->id ?? '—')),
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
    public static function beforeSave($record, array $data): void
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']); // Ne pas mettre à jour le mot de passe s'il est vide
        }

        $record->fill($data);
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereHas('roles', function ($query) {
            $query->where(function ($q) {
                $q->where('role_name', 'Membre')->orWhere('name', 'Membre');
            });
        })->count();
    }
    public static function getNavigationBadgeColor(): string|array|null
    {
        return "success";
    }
}
