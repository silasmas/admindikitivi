<?php

namespace Tests\Feature;

use App\Mail\AgentAccessResetMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Services\AgentAccessResetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Réinitialisation des accès agent (code unique + nouveau mot de passe).
 */
class AgentAccessResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * resetAndNotify génère un code, invalide le MDP et envoie l’e-mail.
     */
    public function test_reset_and_notify_sends_mail_with_code(): void
    {
        Mail::fake();

        $agent = User::factory()->create([
            'email' => 'agent@example.com',
            'password' => 'AncienMotDePasse1!',
        ]);

        $service = app(AgentAccessResetService::class);
        $reset = $service->resetAndNotify($agent);

        $this->assertDatabaseHas('password_resets', [
            'email' => 'agent@example.com',
            'token' => $reset->token,
        ]);

        $agent->refresh();
        $this->assertFalse(Hash::check('AncienMotDePasse1!', $agent->password));

        Mail::assertSent(AgentAccessResetMail::class, function (AgentAccessResetMail $mail) use ($agent, $reset) {
            return $mail->hasTo($agent->email)
                && $mail->code === $reset->token;
        });
    }

    /**
     * Avec le code valide, l’agent peut définir un nouveau mot de passe.
     */
    public function test_complete_with_code_sets_new_password(): void
    {
        Mail::fake();

        $agent = User::factory()->create([
            'email' => 'agent2@example.com',
            'password' => 'AncienMotDePasse1!',
        ]);

        $service = app(AgentAccessResetService::class);
        $reset = $service->resetAndNotify($agent);

        $response = $this->post(route('access.reset.store'), [
            'email' => 'agent2@example.com',
            'code' => $reset->token,
            'password' => 'NouveauMotDePasse2!',
            'password_confirmation' => 'NouveauMotDePasse2!',
        ]);

        $response->assertRedirect('/admin/login');
        $agent->refresh();
        $this->assertTrue(Hash::check('NouveauMotDePasse2!', $agent->password));
        $this->assertDatabaseMissing('password_resets', [
            'email' => 'agent2@example.com',
        ]);
    }

    /**
     * Un code incorrect est refusé.
     */
    public function test_invalid_code_is_rejected(): void
    {
        PasswordReset::create([
            'email' => 'agent3@example.com',
            'token' => 'ABCD1234',
            'former_password' => 'pending-reset',
        ]);

        User::factory()->create(['email' => 'agent3@example.com']);

        $response = $this->from(route('access.reset.show'))->post(route('access.reset.store'), [
            'email' => 'agent3@example.com',
            'code' => 'WRONGCOD',
            'password' => 'NouveauMotDePasse2!',
            'password_confirmation' => 'NouveauMotDePasse2!',
        ]);

        $response->assertRedirect(route('access.reset.show'));
        $response->assertSessionHasErrors('code');
    }
}
