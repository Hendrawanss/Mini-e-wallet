<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_balance')->insert([
            [
                'user_id' => 1,
                'balance' => 50000,
                'balance_achieve' => 1,
            ],
            [
                'user_id' => 2,
                'balance' => 100000,
                'balance_achieve' => 2,
            ],
            [
                'user_id' => 3,
                'balance' => 55000,
                'balance_achieve' => 1,
            ],
        ]);
    }
}
