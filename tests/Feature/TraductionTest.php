<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TraductionTest extends TestCase
{
    use RefreshDatabase;

    public function test_une_connexion_ratee_affiche_un_message_en_francais(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'mauvais-mot-de-passe'])
            ->assertSessionHasErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
    }

    public function test_les_erreurs_de_validation_sont_en_francais(): void
    {
        $this->post('/register', ['name' => 'Ana', 'email' => 'pas-un-email', 'password' => 'motdepasse1', 'password_confirmation' => 'autre'])
            ->assertSessionHasErrors([
                'email' => 'Le champ adresse e-mail doit être une adresse e-mail valide.',
                'password' => 'La confirmation du champ mot de passe ne correspond pas.',
            ]);
    }

    public function test_la_page_profil_est_en_francais(): void
    {
        $this->actingAs(User::factory()->create())->get('/profile')
            ->assertSee('Informations du profil')
            ->assertSee('Supprimer le compte')
            ->assertDontSee('Profile Information');
    }

    public function test_l_e_mail_de_confirmation_est_en_francais(): void
    {
        $mail = (new VerifyEmail)->toMail(User::factory()->unverified()->create());

        $this->assertSame('Confirmez votre adresse e-mail', $mail->subject);
        $this->assertSame('Confirmer mon adresse', $mail->actionText);
    }
}
