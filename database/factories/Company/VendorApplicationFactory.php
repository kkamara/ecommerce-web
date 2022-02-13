<?php

namespace Database\Factories\Company;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Company\VendorApplication;
use App\Models\User\UsersAddress;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company\VendorApplication>
 */
class VendorApplicationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VendorApplication::class;
    
    /**
     * @return int
     */
    private function makeGuest() {
        $decidedBy = User::factory()->state([
            'first_name' => 'guest',
            'last_name' => 'user',
        ])->create();
        return $decidedBy->id;
    }
    
    /**
     * @return int
     */
    private function makeModerator() {
        $decidedBy = User::factory()->state([
            'first_name' => 'mod',
            'last_name' => 'user',
        ])->create();
        $decidedBy->assignRole('moderator');
        return $decidedBy->id;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $address = $user->userAddress()->first();
        if (!$address) {
            $address = UsersAddress::factory()->create();
        }
        $accepted = $decidedBy = $reasonGiven= null;
        if (1 == mt_rand(0, 1)) {
            $accepted = true;
            $decidedBy = $this->makeModerator();
            $reasonGiven = $this->faker->sentence();
        } else if (1 == mt_rand(0, 1)) {
            $accepted = false;
            $decidedBy = $this->makeModerator();
            $reasonGiven = $this->faker->sentence();
        }

        return [
            'user_id' => $this->makeGuest(),
            'proposed_company_name' => $this->faker->company,
            'users_addresses_id' => $address->id,
            'decided_by' => $decidedBy,
            'reason_given' => $reasonGiven,
            'accepted' => $accepted,
        ];
    }
}
