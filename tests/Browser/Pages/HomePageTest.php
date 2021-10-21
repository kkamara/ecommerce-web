<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Product\Product;

class HomePageTest extends DuskTestCase
{
    /**
     * Test products are viewable
     *
     * @return void
     */
    public function testSeeProducts()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home');

            $HomePageProducts = Product::getProducts()->paginate(7);

            foreach ($HomePageProducts as $product) {
                $browser->assertSee($product->name);
            }
        });
    }
}
