<?php

use Illuminate\Database\Seeder;
use App\Traits\UtilTrait;

class TransactionsTableSeeder extends Seeder
{
  use UtilTrait;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
      $exchg_rate = config('variables')['USD_GBP_RATE'];


        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction',
            'amount' => 14,
            'toAmount' => 14*$exchg_rate,
            'recipientID'=>'0987654321',
            'transaction_id' => $this->generateTransactionId(),
            'created_at'=> now(),
            'updated_at'=> now()
        ]);

        DB::table('transactions')->insert([
            'from' => 1,
            'to' => 2,
            'details' => 'sample transaction 2',
            'amount' => 24,
            'toAmount' => 24*$exchg_rate,
            'recipientID'=>'0987654321',
            'transaction_id' => $this->generateTransactionId(),
            'created_at'=> now(),
            'updated_at'=> now()
        ]);

        DB::table('transactions')->insert([
            'from' => 2,
            'to' => 1,
            'details' => 'sample transaction 3',
            'amount' => 15,
            'toAmount' => 15*(1/$exchg_rate),
            'recipientID'=>'1234567890',
            'transaction_id' => $this->generateTransactionId(),
            'created_at'=> now(),
            'updated_at'=> now()
        ]);
    }


}
