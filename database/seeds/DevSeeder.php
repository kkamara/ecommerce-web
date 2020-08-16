<?php

use Illuminate\Database\Seeder;
use App\UserPaymentConfig;
use App\ProductReview;
use App\OrderHistory;
use App\UsersAddress;
use App\Product;
use App\Company;
use App\User;

class DevSeeder extends Seeder
{
    /**
     * @return User
     */
    private function makeModerator() {
        $user = factory(User::class)->create([
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
        $user = factory(User::class)->create([
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
        return factory(User::class)->create([
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
            factory(UserPaymentConfig::class)->create([
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
        return factory(UserPaymentConfig::class)->create([
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
            factory(UsersAddress::class)->create([
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * @param User $user
     * @return Company
     */
    private function makeCompany(User $user) {
        return factory(Company::class)->create(['user_id' => $user->id,]);
    }

    /**
     * @param User $user
     * @param Company $company
     * @param int $count {1000}
     * @return Product|Product[]
     */
    private function makeProducts(User $user, Company $company, int $count=1000) {
        return factory(Product::class, $count)->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);
    }

    /**
     * @param  int $count {2000}
     * @return void
     */
    private function makeProductReviews($count=2000) {
        factory(ProductReview::class, $count)->create();
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
            factory(OrderHistory::class, $count)->create([
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
        $modUser = $this->makeModerator();
        $vendorUser = $this->makeVendor();
        $guestUser = $this->makeGuest();

        $this->setupPaymentConfigs([$modUser, $vendorUser, $guestUser]);
        $this->setupAddressConfigs([$modUser, $vendorUser, $guestUser]);

        $company = $this->makeCompany($vendorUser);
        $this->makeProducts($vendorUser, $company);
        $this->makeProductReviews();
        $this->makeOrderHistories([$guestUser]);
    }
}
