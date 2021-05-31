<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testAuthenticatedUserCannotAccessRegisterPage()
    {
        $user = User::where('email', 'guest@mail.com')->first();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/register')
                ->assertPathIs('/');
        });
    }
}
