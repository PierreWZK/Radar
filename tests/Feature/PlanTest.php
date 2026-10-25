<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class PlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_nouveau_compte_est_sur_le_plan_gratuit(): void
    {
        $user = User::factory()->create();

        $this->assertSame('gratuit', $user->plan);
    }

    public function test_un_compte_non_confirme_est_redirige_hors_du_tableau_de_bord(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/dashboard')->assertRedirect('/verify-email');
    }

    public function test_un_compte_confirme_accede_au_tableau_de_bord(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
    }

    public function test_un_lien_de_confirmation_expire_est_refuse(): void
    {
        $user = User::factory()->unverified()->create();
        $lien = URL::temporarySignedRoute('verification.verify', now()->subMinute(), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)->get($lien)->assertForbidden();
        $this->assertNull($user->fresh()->email_verified_at);
    }
}
