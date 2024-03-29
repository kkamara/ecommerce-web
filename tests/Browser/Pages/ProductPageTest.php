<?php

namespace Tests\Browser\Pages;

use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product\Product;

class ProductPageTest extends DuskTestCase
{
    use WithFaker;
    
    /**
     * @property User
     */
    private $guest;


    /**
     * @property User
     */
    private $vendor;

    public function __construct()
    {
        parent::__construct();
        $this->guest = User::getTestUsers()['guest'];
        $this->vendor = User::getTestUsers()['vendor'];
    }
    
    /**
     * Test product page renders.
     *
     * @return void
     */
    public function testRender()
    {
        $this->browse(function (Browser $browser) {
            $product = Product::inRandomOrder()->first();
            $browser->visitRoute('productShow', [$product->id])
                ->assertSee($product->name);
        });
    }

    /**
     * Test edit product by vendor user.
     *
     * @return void
     */
    public function testEditProductByVendorUser()
    {
        $this->browse(function (Browser $browser) {
            $vendor = User::where('email', $this->vendor['email'])->first();
            $product = Product::inRandomOrder()->first();
            $browser->loginAs($vendor)
                ->visitRoute('productShow', [$product->id])
                ->click('@edit-btn')
                ->type('@product-name', $product->name. ' Test')
                ->click('@submit-btn')
                ->assertRouteIs('productShow', [$product->id])
                ->assertSee(config('flash.company_product.update_success'))
                ->assertSee($product->name.' Test');
        });
    }

    /**
     * Test delete product by vendor user.
     *
     * @return void
     */
    public function testDeleteProductByVendorUser()
    {
        $this->browse(function (Browser $browser) {
            $vendor = User::where('email', $this->vendor['email'])->first();
            $product = Product::inRandomOrder()->first();
            $browser->loginAs($vendor)
                ->visitRoute('productShow', [$product->id])
                ->click('@delete-btn')
                ->select('@choice', 1)
                ->click('@submit-btn')
                ->assertRouteIs('companyProductHome', [$product->company->slug])
                ->assertSee(config('flash.company_product.destroy_y'));
        });
    }

    /**
     * Test creating a product review.
     *
     * @return void
     */
    public function testCreatingAProductReview()
    {
        $this->browse(function (Browser $browser) {
            $guest = User::where('email', $this->guest['email'])->first();
            $product = Product::select('products.id')
                ->boughtBy($guest)
                ->inRandomOrder()
                ->first();
            $content = $this->faker->paragraph();
            $rating = mt_rand(0, 5);

            $browser->loginAs($guest)
                ->visitRoute('productShow', [$product->id])
                ->select('@rating', $rating)
                ->type('@content', $content)
                ->click('@submit-btn')
                ->assertSee(config('flash.product_review.store_success'))
                ->assertSee($content)
                ->assertSee('Product Rated '.$rating.' / 5 by you');
        });
    }
}
