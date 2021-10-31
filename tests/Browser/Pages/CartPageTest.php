<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CartPageTest extends DuskTestCase
{
    /**
     * Test cart page renders.
     *
     * @return void
     */
    public function testRender()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('cartShow')
                ->assertTitleContains('Cart')
                ->assertSee('Your Cart')
                ->assertSee('Your cart is empty.');
        });
    }

    /**
     * Test cart page action.
     *
     * @return void
     */
    public function testAction()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('cartShow')->assertTitleContains('Cart');
        });
    }

    /**
     * Test cart page action when authenticated.
     *
     * @return void
     */
    public function testActionWhenAuthenticated()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('cartShow')->assertTitleContains('Cart');
        });
    }
}
