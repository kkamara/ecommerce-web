<?php

namespace Tests\Browser\Pages;

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

            $products = Product::getProducts()->paginate(7);

            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
        });
    }

    /**
     * Test products are searchable
     *
     * @return void
     */
    public function testSearchProducts()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home');
            $product = Product::inRandomOrder()->first();
            $browser->type('@search-products-in', substr(
                $product->name,
                0,
                mt_rand(5, 15),
            ));
            $browser->click('@search-products-btn');

            $browser->assertSee($product->name);
        });
    }

    /**
     * Test products are viewable via most pop filter
     *
     * @return void
     */
    public function testSeeProductsMostPop()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/products?sort_by=pop');

            $products = Product::getProducts('', 'pop')->paginate(7);

            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
        });
    }

    /**
     * Test products are viewable via top rated filter
     *
     * @return void
     */
    public function testSeeProductsTopRated()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/products?sort_by=top');

            $products = Product::getProducts('', 'top')->paginate(7);

            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
        });
    }
}
