<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Order\OrderHistoryProducts;

class CartPageTest extends DuskTestCase
{
    use WithFaker;

    /**
     * @property User
     */
    private $user;

    /**
     * @property OrderHistoryProducts
     */
    private $orderHistoryProducts;

    public function __construct()
    {
        parent::__construct();
        $this->user = User::getTestUsers()['guest'];
        $this->orderHistoryProducts = new OrderHistoryProducts;
    }
    
    /**
     * Test cart page renders.
     *
     * @return void
     */
    public function testRender()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('cartShow')
            ->screenshot('cart-show')
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
            $browser->visitRoute('home')
                ->click('@product-1');
            $productName = $browser->text('@product-name');
            $browser->click('@add-to-cart-btn')
                ->visitRoute('cartShow')
                ->assertSee($productName)
                ->click('@proceed-to-checkout-btn')
                ->type('@email', $this->user['email'])
                ->click('@login-btn')
                ->screenshot('address-checkbox')
                ->click('@choose-this-address-checkbox')
                ->type('@ccv-number-1', 444)
                ->click('@choose-this-card-checkbox-1')
                ->click('@pay-your-order-btn');
            $OrderHistoryProduct = $this->orderHistoryProducts
                ->latest()
                ->first();
            $orderHistory = $OrderHistoryProduct->orderHistory;
            $browser->assertSee($productName)
                ->assertSee('For Your Reference: '.$orderHistory->reference_number);
            $OrderHistoryProduct->delete();
            $orderHistory->delete();
        });
    }
}
