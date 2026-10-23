<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use App\Services\AgentAccessResetService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use RuntimeException;

/**
 * Édition d’un agent Filament.
 */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Actions d’en-tête (réinitialisation des accès + suppression).
     *
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('resetAccess')
                ->label('Réinitialiser les accès')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Réinitialiser les accès de cet agent')
                ->modalDescription('L’ancien mot de passe sera invalidé. Un e-mail contenant un code unique sera envoyé pour créer un nouveau mot de passe (valable 24 h).')
                ->modalSubmitActionLabel('Envoyer le code')
                ->action(function () {
                    /** @var User $record */
                    $record = $this->getRecord();

                    try {
                        app(AgentAccessResetService::class)->resetAndNotify($record);

                        Notification::make()
                            ->title('Accès réinitialisés')
                            ->body('Un e-mail avec le code unique a été envoyé à ' . $record->email . '.')
                            ->success()
                            ->send();
                    } catch (RuntimeException $e) {
                        $isPartial = str_contains($e->getMessage(), 'Code à transmettre');
                        Notification::make()
                            ->title($isPartial ? 'Code généré (e-mail non envoyé)' : 'Échec de la réinitialisation')
                            ->body($e->getMessage())
                            ->{$isPartial ? 'warning' : 'danger'}()
                            ->persistent()
                            ->send();
                    } catch (\Throwable $e) {
                        report($e);

                        Notification::make()
                            ->title('Échec de l’envoi')
                            ->body('Impossible d’envoyer l’e-mail. Vérifiez la configuration mail du serveur.')
                            ->danger()
                            ->send();
                    }
                }),
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Prépare les données avant mise à jour.
     * Mot de passe vide = inchangé ; sinon mutator User hashe.
     *
     * @param  array<string, mixed>  $data  Données du formulaire
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
