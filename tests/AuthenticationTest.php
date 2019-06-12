<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;

class AuthenticationTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUserRegistration()
    {
        $user = ['name'=>'Mike','email'=>'baswetima@gmail.com','password'=>'my_password'];
        $response = $this->post('/api/v1/users', $user);
        $response->assertResponseStatus(201);
    }

    public function testUserRegistrationWithoutFields()
    {
        $user = [];
        $response = $this->post('/api/v1/users', $user);
        $response->assertResponseStatus(422);
    }

    public function testLogin()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $credentials = [
            'email' => $user->email,
            'password' => $password
        ];
        $response = $this->post('/api/v1/login', $credentials);
        $response->assertResponseStatus(200);
    }

    public function testLoginWithInvalidCredentials()
    {

        $credentials = [
            'email' => 'basweti@gmail.com',
            'password' => 'password'
        ];
        $response = $this->post('/api/v1/login', $credentials);
        $response->assertResponseStatus(400);
    }
    public function testDeleteUserWithoutAuthentication()
    {
        $user = factory(User::class)->create([
            'password' => app('hash')->make($password = 'i-love-laravel'),
        ]);
        $this->be($user);
        $response = $this->delete("/api/v1/users/{$user->id}");
        $response->assertResponseStatus(401);
    }
}

