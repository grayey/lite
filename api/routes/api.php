<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * ACCOUNT
 */
Route::post('/login', 'AccountsController@login')->name('public.login');
Route::post('/account', 'AccountsController@createAccount')->name('public.create-account');





/**
 * TRANSACTIONS
 */
Route::get('/accounts/{account}/transactions', 'AccountsController@getAccountTransactions')->name('list.account.transactions');
Route::post('/accounts/{account}/transactions', 'AccountsController@transferFunds')->name('store.account.transactions');
Route::get('/check-currency/{accountId}', 'AccountsController@checkCurrency')->name('checkcurrency');



/**
 * CURRENCIES
 */
 Route::get('/currencies', 'CurrencyController@index')->name('list.currencies');
