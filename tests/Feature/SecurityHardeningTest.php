<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Couvre les garde-fous P0/P1 : auth uploads, panel, mass assignment, hashing.
 */
class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Upload vidéo anonyme → redirection login.
     */
    public function test_upload_video_requires_authentication(): void
    {
        $response = $this->post('/upload-video', [
            'video' => UploadedFile::fake()->create('clip.mp4', 100, 'video/mp4'),
        ]);

        $response->assertRedirect();
        $this->assertGuest();
    }

    /**
     * Symlink anonyme → redirection login.
     */
    public function test_symlink_requires_authentication(): void
    {
        $this->get('/symlink')->assertRedirect();
        $this->assertGuest();
    }

    /**
     * Chunk upload anonyme refusé.
     */
    public function test_chunk_upload_requires_authentication(): void
    {
        $this->post('/upload-video-chunk', [
            'chunk' => UploadedFile::fake()->create('part.bin', 10),
            'index' => 0,
            'uploadId' => '11111111-1111-1111-1111-111111111111',
        ])->assertRedirect();
    }

    /**
     * api_token n’est pas mass-assignable via fill() (hors Factory::unguarded).
     */
    public function test_user_api_token_is_not_mass_assignable(): void
    {
        $user = User::factory()->create();
        $user->fill(['api_token' => 'should-not-stick', 'firstname' => 'Ada']);
        $user->save();

        $fresh = $user->fresh();
        $this->assertNull($fresh->api_token);
        $this->assertSame('Ada', $fresh->firstname);
    }

    /**
     * Un seul hash bcrypt (pas de double hash).
     */
    public function test_password_is_hashed_once(): void
    {
        $user = User::factory()->create([
            'password' => 'plain-secret-99',
        ]);

        $this->assertTrue(Hash::check('plain-secret-99', $user->fresh()->password));
        $this->assertFalse(Hash::check('plain-secret-99', Hash::make($user->password)));
    }

    /**
     * Membre n’accède pas au panel ; super_admin oui.
     */
    public function test_can_access_panel_excludes_membre(): void
    {
        $panel = Filament::getPanel('admin');

        $membreRole = Role::create([
            'name' => 'membre',
            'guard_name' => 'web',
            'role_name' => 'Membre',
        ]);
        $adminRole = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'role_name' => 'Super Administrateur',
        ]);

        $membre = User::factory()->create();
        $membre->assignRole($membreRole);

        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        $this->assertFalse($membre->canAccessPanel($panel));
        $this->assertTrue($admin->canAccessPanel($panel));
    }

    /**
     * Delete legacy en GET n’est plus accepté.
     */
    public function test_legacy_delete_no_longer_accepts_get(): void
    {
        $adminRole = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
            'role_name' => 'Super Administrateur',
        ]);
        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole($adminRole);

        $this->actingAs($admin)
            ->get('/deleteMedia/1')
            ->assertMethodNotAllowed();
    }

    /**
     * Helper s3_media_url retourne une URL disque quand les signatures sont off.
     */
    public function test_s3_media_url_helper_returns_disk_url_when_unsigned(): void
    {
        Storage::fake('s3');
        config(['filesystems.disks.s3.visibility' => 'private']);

        $url = s3_media_url('videos/demo.mp4');

        $this->assertIsString($url);
        $this->assertStringContainsString('videos/demo.mp4', $url);
    }
}
