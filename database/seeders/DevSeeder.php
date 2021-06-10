<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Seeder;
use App\Models\User\UserPaymentConfig;
use App\Models\Product\ProductReview;
use App\Models\Order\OrderHistory;
use App\Models\User\UsersAddress;
use App\Models\Product\Product;
use App\Models\Company\Company;
use App\Models\User;

class DevSeeder extends Seeder
{
    /**
     * @return User
     */
    private function makeModerator() {
        $user = User::factory()->create([
            'first_name' => 'Mod',
            'last_name' => 'User',
            'email' => 'mod@mail.com',
        ]);
        $user->assignRole('moderator');
        return $user;
    }

    /**
     * @return User
     */
    private function makeVendor() {
        $user = User::factory()->create([
            'first_name' => 'Vendor',
            'last_name' => 'User',
            'email' => 'vendor@mail.com',
        ]);
        $user->assignRole('vendor');
        return $user;
    }

    /**
     * @return User
     */
    private function makeGuest() {
        return User::factory()->create([
            'first_name' => 'Guest',
            'last_name' => 'User',
            'email' => 'guest@mail.com',
        ]);
    }

    /**
     * @param User[] $users
     * @return void
     */
    private function setupPaymentConfigs($users) {
        foreach($users as $user) {
            Log::debug($user);
            UserPaymentConfig::factory()->create([
                'user_id' => $user->id,
                'card_holder_name' => $user->name
            ]);
        }
    }

    /**
     * @param User $user
     * @return void
     */
    private function setupPaymentConfig($user) {
        return UserPaymentConfig::factory()->create([
            'user_id' => $user->id,
            'card_holder_name' => $user->name
        ]);
    }

    /**
     * @param User[] $users
     * @return void
     */
    private function setupAddressConfigs($users) {
        foreach($users as $user) {
            UsersAddress::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * @param User $user
     * @return Company
     */
    private function makeCompany(User $user) {
        return Company::factory()->create(['user_id' => $user->id,]);
    }

    /**
     * @param User $user
     * @param Company $company
     * @param int $count {1000}
     * @return Product|Product[]
     */
    private function makeProducts(User $user, Company $company, int $count=1000) {
        $products = Product::factory()->count($count)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);
        foreach($products as $product) {
            $this->makeProductReviews($product->id);
        }
        return $products;
    }

    /**
     * @param  String $productId
     * @param  int $limit {30}
     * @return void
     */
    private function makeProductReviews($productId, $limit=30) {
        /** @var Int $count */
        $count = mt_rand(0, $limit);
        ProductReview::factory()->count($count)->create([
            'product_id' => $productId,
        ]);
    }

    /**
     * @param User[] $user
     */
    private function makeOrderHistories($users, $count=null) {
        if (!$count) {
            $count = mt_rand(5,10);
        }
        foreach($users as $user) {
            $usersPaymentConfig = UserPaymentConfig::where('user_id', $user->id)
                ->inRandomOrder()
                ->first() ?:
                $this->setupPaymentConfig($user);
            OrderHistory::factory()->count($count)->create([
                'user_id' => $user->id,
                'user_payment_config_id' => $usersPaymentConfig->id,
            ]);
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array(
            'mod' => $this->makeModerator(),
            'vendor' => $this->makeVendor(),
            'guest' => $this->makeGuest(),
        );

        $this->setupPaymentConfigs(array_values($users));
        $this->setupAddressConfigs(array_values($users));

        $company = $this->makeCompany($users['vendor']);
        $this->makeProducts($users['vendor'], $company);
        $this->makeOrderHistories([$users['guest']]);
    }
}
