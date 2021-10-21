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
        $guestUserCreds = User::getTestUsers()['guest'];
        $guest = User::where('email', $guestUserCreds['email'])->first();

        $this->browse(function (Browser $browser) use ($guest) {
            $browser->loginAs($guest)
                ->visitRoute('registerHome')
                ->assertPathIs('/');
        });
    }
}
