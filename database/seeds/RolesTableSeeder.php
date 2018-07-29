<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

// To be run once and after PermissionsTableSeeder
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'moderator']);
    }
}
