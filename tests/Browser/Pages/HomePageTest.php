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

            $this->assertProductsOnPage($browser);
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
     * Test empty search message
     *
     * @return void
     */
    public function testEmptySearchProducts()
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home');
            $browser->type('@search-products-in', 11111);
            $browser->click('@search-products-btn');

            $browser->assertSee('There are no products currently available.');
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
            $sortBy = 'pop';
            $browser->visit('/products?sort_by='.$sortBy);
            $this->assertProductsOnPage($browser, $sortBy);
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
            $sortBy = 'top';
            $browser->visit('/products?sort_by='.$sortBy);
            $this->assertProductsOnPage($browser, $sortBy);
        });
    }
    
    /**
     * Test products are viewable via top rated filter
     *
     * @param Browser $browser
     * @param String  $sortBy (optional)
     * @return void
     */
    private function assertProductsOnPage(Browser $browser, String $sortBy='')
    {
        $products = Product::getProducts('', $sortBy)->paginate(7);

        foreach ($products as $product) {
            $browser->assertSee($product->name);
        }
    }
}
