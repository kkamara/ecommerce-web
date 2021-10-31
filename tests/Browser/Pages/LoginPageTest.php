<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginPageTest extends DuskTestCase
{
    /**
     * @property User
     */
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->user = User::getTestUsers()['guest'];
    }

    /**
     * Test register page renders.
     *
     * @return void
     */
    public function testRender()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login')
                ->assertTitleContains('Login')
                ->assertSee('Email')
                ->assertSee('Password');
        });
    }

    /**
     * Test register page action.
     *
     * @return void
     */
    public function testAction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('login');
            
            $browser->type('@email', $this->user['email']);
            $browser->type('@password', $this->user['password']);
            $browser->click('@login-btn');

            $browser->assertRouteIs('home');
        });
    }
}
