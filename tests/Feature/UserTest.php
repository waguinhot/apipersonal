<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
     public function test_store_user()
    {
        $response = $this->post(route('register.user'), [
            'name' => 'fake name',
            'email' => 'fake@email.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123'
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users',['email' => 'fake@email.com']);
    }

    /**
     * @return void
     */
    public function test_it_not_valid_email()
    {
        $response = $this->post(route('register.user'), [
            'name' => 'fake name',
            'email' => 'fake.com',
            'password' => 'secret123'
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('email' , 'The email must be a valid email address.');

    }

    /**
     * @return void
     */
    public function test_it_password_not_confirmed()
    {
        $response = $this->post(route('register.user'), [
            'name' => 'fake name',
            'email' => 'fake@email.com',
            'password' => 'secret123',
        ]);

        $response
            ->assertStatus(302)
            ->assertSessionHasErrors('password' , 'The password confirmation does not match.');
    }

    /**
     * @return void
     */
    public function test_it_email_has_ben_used()
    {
        $userone=  [
            'name' => 'fake name',
            'email' => 'fake@email.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $response = $this->post(route('register.user'), $userone);

        $response2 = $this->post(route('register.user'), $userone);

        $response2->assertStatus(302);
        $response2->assertSessionHasErrors('email','The email has already been taken.');
    }
}
