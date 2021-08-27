<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function testRegistrationCanBeRendered()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function testCanNewUserRegister()
    {
        $response = $this->post('/register', [
            'name'=>'test username',
            'email'=>'test@test.com',
            'password'=>'password',
            'password_confirmation'=>'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $response->assertStatus(302);
    }

}
