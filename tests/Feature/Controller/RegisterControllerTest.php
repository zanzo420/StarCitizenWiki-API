<?php

namespace Tests\Feature\Controller;

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Class RegisterControllerTest
 * @package Tests\Feature\Controller
 */
class RegisterControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @var  RegisterController */
    private $controller;

    /**
     * Resolve the Controller
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->controller = resolve(RegisterController::class);
    }

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController::register()
     * @covers \App\Events\UserRegistered
     */
    public function testRegistration()
    {
        $response = $this->post('register', [
            'email' => str_random(5).'@'.str_random(5).'.de',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('account');
    }

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController::create()
     */
    public function testCreate()
    {
        $name = str_random(6);
        $email = strtolower($name).'@'.strtolower($name).'.de';
        $user = $this->controller->create([
            'name' => $name,
            'email' => $email,
            'api_token' => str_random(60),
            'password' => bcrypt($name),
            'requests_per_minute' => 60,
            'last_login' => date('Y-m-d H:i:s'),
        ]);

        $this->assertEquals($email, $user->email);
    }

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController::showRegistrationForm()
     */
    public function testRegistrationFormView()
    {
        $response = $this->get('register');
        $response->assertStatus(302);
    }
}
