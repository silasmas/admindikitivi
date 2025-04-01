<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Carbon;
use Filament\Widgets\TableWidget;
// use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;// use Filament\Tables\Widgets\TableWidget;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;

abstract class RoleStatBaseWidget extends TableWidget
{
    abstract protected function getRoleName(): string;

    protected function baseQuery()
    {
        return DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', $this->getRoleName());
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('label')->label('Donnée'),
            TextColumn::make('total')->label('Total'),
        ];
    }
    public function query(): EloquentCollection
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

        return EloquentCollection::make($rows);
    }
}
