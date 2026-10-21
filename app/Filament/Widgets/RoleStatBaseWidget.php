<?php

namespace App\Filament\Widgets;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;

/**
 * Widget tabulaire de stats filtré par rôle (via model_has_roles).
 */
abstract class RoleStatBaseWidget extends TableWidget
{
    /**
     * Libellé métier du rôle (ex. Membre).
     *
     * @return string
     */
    abstract protected function getRoleName(): string;

    /**
     * Query de base des utilisateurs du rôle.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function baseQuery()
    {
        return usersByRoleQuery($this->getRoleName());
    }

    /**
     * Colonnes du tableau.
     *
     * @return array
     */
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('label')->label('Donnée'),
            TextColumn::make('total')->label('Total'),
        ];
    }

    /**
     * Répartition par genre pour le rôle Membre.
     *
     * @return EloquentCollection
     */
    public function query(): EloquentCollection
    {
        $rows = usersByRoleQuery('Membre')
            ->select('users.gender as label', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('users.gender')
            ->get()
            ->map(fn ($item) => [
                'label' => ucfirst($item->label ?? 'Non spécifié'),
                'total' => $item->total,
            ]);

        return EloquentCollection::make($rows);
    }
}
