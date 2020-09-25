<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AccountsTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


/**
 * [getAccountTransactions description]
 * @return [type] [description]
 * This method tests the getAccountTransactions endpoint
 */
    public function getAccountTransactions(){
      $accountId =  1;
      $requestUrl = "/accounts/$accountId/trancations";
      $response = $this->get($requestUrl);
      $response->assertStatus(200);
    }



    public function createAccount(){
      $accountData = [
        'name' => 'johnnydepp',
       'account_id'=>'1234567890',
       'currency_id'=>1,
       'balance' => 1200.00
     ];
      $response = $this->post('/account', $accountData);
      $response
            ->assertStatus(201)
            ->assertJson([
                'created' => true,
            ]);
    }
}
