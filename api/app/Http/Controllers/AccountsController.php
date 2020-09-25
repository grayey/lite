<?php

namespace App\Http\Controllers;

use App\Accounts;
use App\Transactions;
use Illuminate\Http\Request;
use App\Traits\RestApiTrait;
use App\Traits\UtilTrait;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class AccountsController extends Controller{

  use RestApiTrait, UtilTrait;

  private $with = ['currency']; // used to load relationships
  private $variables;


  public function __construct(){
       RestApiTrait::$entity = Accounts::class;
       $this->variables = config('variables');
   }



    public function createAccount(Request $request){

      $newAccount = [
        "name"=>$request->input('userName'),
        "currency_id" => $request->input('currency'),
        "balance"=> 1000.00, // Free money for first timers!;
        "account_id"=>strval(mt_rand(1111111111, 9999999999)) // 10 digit account number
      ];
        $id = $this->store($newAccount, $getId = true);
        return $this->list($id, $with =  $this->with);
    }

   /**
    * [loginOrCreateAccount description]
    * @param  Request $request
    * @return [Json HttpResponse]
    * [An account is created for a non-existent user because 'Authentication is NOT in the scope of this assignment']
    */
     public function login(Request $request){
       $accountId = $request->input('account');
       $account = Accounts::where('account_id', $accountId)->firstOrFail();
        return $this->list($id = $account->id, $with = $this->with);
     }


      /**
       * [getAccountTransactions description]
       * @param  Accounts $account [description]
       * @return [type]            [Json HttpResponse]
       *
       * This method returns an account's transactions
       */
       public function getAccountTransactions(Accounts $account){
         $accountId = $account->id;
         $transactions = Transactions::where('from',$accountId)->orWhere('to', $accountId)->get();
         return response()->json(
           [
             "data" => $transactions,
             "code"=>"OK"
           ]
         );
       }


       public function transferFunds(Request $request, Accounts $account){
         $toAmount = $request->input('to_amount');
         $amount = $request->input('amount');
         $to = $request->input('to');
         $details = $request->input('details');
         $recipient = Accounts::where('account_id',$to)->firstOrFail();

         $crook = $account->id == $recipient->id;
         $insufficient = $amount > $account->balance;
         $failure = null;
         if($crook){
           $failure = "You can't send money to yourself. Don't be a crook!";
         }elseif ($insufficient) {
           $failure = "'Insufficient Account Balance!'";

         }
         if($failure){
            throw new BadRequestHttpException($failure);
         }

         try{
           $account->balance -= $amount;
           $recipient->balance += $toAmount; // To make up for diff currencies

           $account->save(); // debit sender
           $recipient->save(); // credit receiver

           $transactionData = [
             'from' => $account->id,
             'to' => $recipient->id,
             'details' => $details,
             'recipientID'=>$recipient->account_id,
             'toAmount' => $toAmount,
             'amount'=>$amount,
             'transaction_id' => $this->generateTransactionId()
           ];

           return $this->createTransaction($transactionData);
         }catch(Exception $e){
           throw $e;
         }


    }


/**
 * [checkCurrency description]
 * @param  Request $request   [description]
 * @param  [type]  $accountId [description]
 * @return [type]             [Json HttpResponse]
 */

    public function checkCurrency(Request $request, $accountId){
      $account = Accounts::where('account_id',$accountId)->firstOrFail();
      $currency = $account->currency->name;
      $data = [
        "currency"=>$currency,
        "exchg_rate"=>$this->variables['USD_GBP_RATE']
      ];
      return response()->json([
        "data"=>$data,
        "code" =>'OK'
      ]);



    }


    /**
     * [createTransaction description]
     * @param  [type] $transaction [description]
     * @return [type]              [Json HttpResponse]
     * This method creates a new transaction
     */
       private function createTransaction($transaction){

        RestApiTrait::$entity = Transactions::class;
        return $this->store($transaction);

       }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function show(Accounts $accounts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function edit(Accounts $accounts)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Accounts $accounts)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Accounts  $accounts
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accounts $accounts)
    {
        //
    }




}
