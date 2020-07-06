<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('balance_bank')->insert([
            [
                'name' => 'Bank 1',
                'balance' => 5000000,
                'balance_achieve' => 1,
                'code' => '071',
                'enable' => true
            ],
            [
                'name' => 'Bank 2',
                'balance' => 1000000,
                'balance_achieve' => 2,
                'code' => '072',
                'enable' => true
            ],
            [
                'name' => 'Bank 3',
                'balance' => 2000000,
                'balance_achieve' => 3,
                'code' => '073',
                'enable' => true
            ],
        ]);
    }
}
