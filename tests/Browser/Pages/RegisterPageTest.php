<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Carbon\Carbon;
use App\Models\User;

class RegisterPageTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @property String
     */
    private $password = "secret";

    /**
     * Test register page renders.
     *
     * @return void
     */
    public function testRender()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('registerHome')->assertSee('Basic Details');
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
            $browser->visitRoute('registerHome');
            
            $firstName = $this->faker->firstName();
            $lastName  = $this->faker->lastName();
            $email     = $this->faker->freeEmail();
            $date      = Carbon::now()->addYears(3);
            
            $browser->type('@first-name', $firstName);
            $browser->type('@last-name', $lastName);
            $browser->type('@email', $email);
            $browser->type('@password', $this->password);
            $browser->type('@password-confirm', $this->password);
            $browser->type('@building-number', $this->faker->buildingNumber());
            $browser->type('@street-address-1', $this->faker->streetAddress());
            $browser->type('@postcode', $this->faker->postcode());
            $browser->type('@city', $this->faker->city());
            $browser->type('@country', $this->faker->country());
            $browser->type('@phone-number-extension', "+44");
            $browser->type('@phone-number', $this->faker->phoneNumber());
            $browser->type('@card-holder-name', $firstName.' '.$lastName);
            $browser->type('@card-number', $this->getCreditCardNumber());
            $browser->type('@expiry_date', $date->format('MMMM'));
            $browser->keys('@expiry_date', ['{tab}']);
            $browser->type('@expiry_date', $date->format('Y'));
            $browser->click('@register-btn');

            $browser->assertRouteIs('home');
            $browser->screenshot('test-register-action');

            User::where('email', $email)->delete();
        });
    }

    /**
     * @return String
     */
    private function getCreditCardNumber() {
        $creditCardNumber = null;
        while (null === $creditCardNumber) {
            $tmp = $this->faker->creditCardNumber();
            if (16 === strlen($tmp)) {
                $creditCardNumber = $tmp;
            }
        }
        return $creditCardNumber;
    }
}
