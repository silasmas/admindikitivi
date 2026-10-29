<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlaybackEventResource\Pages;
use App\Models\PlaybackEvent;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Logs Admin (slide 9) : lectures, fins de vidéo, abandons, erreurs, buffering,
 * clics sur recommandations et mesures de vitesse envoyés par les applications.
 * Lecture seule : les logs sont écrits uniquement par l'API.
 */
class PlaybackEventResource extends Resource
{
    protected static ?string $model = PlaybackEvent::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';
    protected static ?string $navigationLabel = 'Logs de lecture';
    protected static ?string $modelLabel = 'log de lecture';
    protected static ?string $pluralModelLabel = 'logs de lecture';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                TextColumn::make('event')
                    ->label('Événement')
                    ->formatStateUsing(fn ($state) => PlaybackEvent::EVENT_LABELS[$state] ?? $state)
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'playback_error', 'api_error' => 'danger',
                        'buffer_start', 'buffer_end', 'video_abandon' => 'warning',
                        'video_complete' => 'success',
                        default => 'info',
                    }),
                TextColumn::make('media.media_title')
                    ->label('Média')
                    ->limit(30)
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('value_ms')
                    ->label('Durée (ms)')
                    ->numeric()
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('user.firstname')
                    ->label('Profil')
                    ->placeholder('Visiteur'),
                TextColumn::make('platform')
                    ->label('Plateforme')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                TextColumn::make('device_class')
                    ->label('Écran')
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('app_version')
                    ->label('Version')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('props')
                    ->label('Détails')
                    ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_UNESCAPED_UNICODE) : (string) $state)
                    ->limit(60)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->filters([
                SelectFilter::make('event')
                    ->label('Événement')
                    ->multiple()
                    ->options(PlaybackEvent::EVENT_LABELS),
                SelectFilter::make('platform')
                    ->label('Plateforme')
                    ->options(['android' => 'Android', 'ios' => 'iOS', 'web' => 'Web']),
                Filter::make('occurred_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('until')->label('Au'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'] ?? null, fn (Builder $q, $date) => $q->whereDate('occurred_at', '>=', $date))
                        ->when($data['until'] ?? null, fn (Builder $q, $date) => $q->whereDate('occurred_at', '<=', $date))),
            ], layout: FiltersLayout::AboveContent)
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlaybackEvents::route('/'),
        ];
    }
}
