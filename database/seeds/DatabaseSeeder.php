<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	     factory(App\User::class)->create([
		    'name' => 'admin',
		    'email' => 'payments@market.biz',
		    'active' => 1,
	    ]);
    }
}
