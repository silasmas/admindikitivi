<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class GenderStatsWidget extends TableWidget
{
    protected static ?string $heading = 'Répartition par sexe (clients)';
    protected static ?string $maxWidth = 'full';
    protected static ?int $sort = 1;
    public function query(): Collection
    {
        $rows = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'Membre')
            ->select('users.gender as label', DB::raw('COUNT(*) as total'))
            ->groupBy('users.gender')
            ->get()
            ->map(fn ($item) => [
                'label' => ucfirst($item->label ?? 'Non spécifié'),
                'total' => $item->total,
            ]);

        return Collection::make($rows);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('label')->label('Genre'),
            TextColumn::make('total')->label('Total'),
        ];
    }
}

