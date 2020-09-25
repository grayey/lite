<?php

use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('accounts')->insert([
            'name' => 'John',
            'balance' => 15000,
            'account_id'=>'1234567890',
            'currency_id'=>1
        ]);

        DB::table('accounts')->insert([
            'name' => 'Peter',
            'balance' => 100000,
            'account_id'=>'0987654321',
            'currency_id'=>2
        ]);
    }
}
