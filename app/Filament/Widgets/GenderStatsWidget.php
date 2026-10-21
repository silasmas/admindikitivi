<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Facades\DB;

class GenderStatsWidget extends TableWidget
{
    protected static ?string $heading = 'Répartition par sexe (clients)';

    protected static ?string $maxWidth = 'full';

    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->join('model_has_roles', function ($join) {
                        $join->on('users.id', '=', 'model_has_roles.model_id')
                            ->where('model_has_roles.model_type', '=', User::class);
                    })
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->where(function ($q) {
                        $q->where('roles.name', 'Membre')
                            ->orWhere('roles.role_name', 'Membre')
                            ->orWhere('roles.name', 'membre');
                    })
                    ->select('users.gender as label', DB::raw('COUNT(*) as total'))
                    ->groupBy('users.gender')
            )
            ->columns([
                TextColumn::make('label')
                    ->label('Genre')
                    ->formatStateUsing(fn (?string $state): string => ucfirst($state ?? 'Non spécifié')),
                TextColumn::make('total')->label('Total'),
            ]);
    }
}

