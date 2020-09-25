

<template>

<div>



<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">New Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <b-form @submit.prevent="transferFunds">

            <div class="modal-body">
              <b-form-group id="input-group-1" label="To (Recipient):" label-for="input-1">
                  <b-form-input
                    id="input-1"
                    size="sm"
                    v-model="payment.to"
                    type="number"
                    required
                    placeholder="Destination ID"
                    :change="checkCurrency()"
                  ></b-form-input>

                  <div v-if="currencyData.mismatch">
                    <small class="text-primary">
                      {{currencyData.msg}}
                    </small>
                  </div>

                </b-form-group>

                <b-form-group id="input-group-2" label="Amount:" label-for="input-2">
                  <b-input-group :prepend="account.currency.name == 'USD' ?'$' : '€'" size="sm">
                    <b-form-input
                      id="input-2"
                      v-model="payment.amount"
                      type="number"
                      required
                      placeholder="Amount"
                      :disabled="!currencyData.isChecked"
                      :change="setConversionMsg()"
                    ></b-form-input>
                  </b-input-group>
                  <div v-if="currencyData.mismatch">
                    <small class="text-primary">
                      {{conversionMsg}}
                    </small>
                  </div>
                </b-form-group>

                <b-form-group id="input-group-3" label="Details:" label-for="input-3">
                  <b-form-input
                    id="input-3"
                    size="sm"
                    v-model="payment.details"
                    required
                    placeholder="Payment details"
                    :disabled="!currencyData.isChecked"
                  ></b-form-input>
                </b-form-group>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <b-button type="submit" size="md" :variant="formIsInvalid() ? 'primary' :'success'" :disabled="paying || formIsInvalid()">Pay <i class="fa fa-cog fa-spin" v-if="paying"></i></b-button>
            </div>

        </b-form>

    </div>
  </div>
</div>


    <div class="container mb-3">
        <b-card :header="'Account Information'" class="mt-3">
            <b-card-text>


                <div>
                    Account: <code>{{ account.account_id }}</code>
                </div>
                <div>
                    Balance:
                    <code>{{ account.currency.name === "USD" ? "$" : "€"
              }}{{ formatAmount(account.balance) }}</code
            >
          </div>
        </b-card-text>
      </b-card>

    </div>



    <div class="container">
      <div class="card">
        <div class="card-header">
          Transaction History

          <b-button size="sm" variant="success" @click="toggleModal('paymentModal')" class="float-right">New payment <i class=" fa fa-plus"></i> </b-button>

        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th class="text-right">Amount ({{account.currency.name == 'USD' ? '$' : '€'}})</th>
                  <th>Recipient</th>
                  <th>Type</th>
                  <th>Details</th>
                  <th>Transaction Ref.</th>
                  <th>Transaction Date</th>
                </tr>
              </thead>

              <tbody>
                <tr v-if="transactions.length == 0">
                <td colspan="7" class="text-center">
                  <span v-if="loading"> Fetching records.. <i class="fa fa-cog fa-spin"></i> </span>
                  <span v-if="!loading"> No payment records found.</span>
                </td>
                </tr>

                <tr v-for="(transaction, idx) in transactions" :key="transaction.id">
                  <td><b>{{idx+1}}.</b></td>
                  <td class="text-center">{{transaction.type == 'Debit' ? formatAmount(transaction.amount) : formatAmount(transaction.toAmount)}}</td>
                  <td>{{transaction.type == 'Debit' ? transaction.recipientID : account.account_id }} <span class="badge badge-success" v-if="transaction.type == 'Credit'"><small>you</small></span></td>
                  <td>{{transaction.type}} &nbsp;
                    <i v-if="transaction.type == 'Credit'" class="fa fa-arrow-up text-success"></i>
                    <i v-if="transaction.type == 'Debit'" class="fa fa-arrow-down text-danger"></i>
                    </td>
                  <td>{{transaction.details}}</td>
                  <td>{{transaction.transaction_id}}</td>
                  <td>{{formatDate(transaction.created_at)}}</td>

                </tr>
              </tbody>
            </table>
          </div>


        </div>


      </div>
    </div>
  </div>

</template>

<script lang="ts">

import Vue from "vue";
import axios from "axios"
import * as AppService from "../../../services/appMain.service";
import * as StorageService  from "../../../services/storage.service";
import  moment from "moment";

declare const $:any;



export default Vue.extend({
    layout: 'dashboard',


    data() {
            return {
                show: false,
                payment: {
                  to:'',
                  details:null,
                  amount:null
                },

                account: {
                  currency:{},
                  balance:null
                },
                transactions: [],
                loading: true,
                paying:false,
                conversionMsg:'',
                currencyData:{
                  mismatch:false,
                  isChecked:false,
                  exchg_rate:1.0,
                  msg:''
                }
            };
        },

        async mounted() {
          this.account = StorageService.getActiveAccount();
          this.getAllAccountTransactions();
        },

        methods: {


              /**
               * This method gets account transactions
               */
          async getAllAccountTransactions(){
              const account:any = this.account;
              const accountId = account.account_id;
              this.loading = true;
              await AppService.getTransactionsByAccountId(account.id).then(
                (transactionsResponse:any)=>{
                  this.loading = false;
                  const transactions = transactionsResponse.data;
                  transactions.forEach((transaction:any) =>{
                      transaction.type = transaction.recipientID == accountId ? 'Credit' : 'Debit';
                  })
                  this.transactions = transactions;

                }).catch(
                (error:any)=>{
                  this.loading = false;

              })

          },

            /**
             * This method transfers funds
             */
            async transferFunds(){
              const account:any = this.account;
              const payment:any = {...this.payment};
              if(payment.amount > account.balance){
                return alert('Insufficient funds!');
              }
              this.paying = true;
              payment.to_amount = payment.amount * this.currencyData.exchg_rate; // Separate  the sending amount
              await AppService.transferFunds(account.id,payment).then(
                (transactionsResponse:any)=>{
                  this.paying= false;
                  const transactions:any = this.transactions;
                  const transaction = transactionsResponse.data;
                  transaction.type = transaction.recipientID == account.account_id ? 'Credit' : 'Debit';
                  transactions.unshift(transaction);
                  this.transactions = transactions;
                  this.toggleModal('paymentModal', false);

                  // Update account balance
                  account.balance -= payment.amount;
                  this.account  = account;
                  StorageService.setActiveAccount(this.account);

                  // Reset form
                  this.resetPaymentForm();
                  this.currencyData.mismatch = false;

                }).catch(
                (error:any)=>{
                  this.paying= false;
                  const errorMsg:string = error.data ? error.data.message : 'An error occurred. Please try again later.';
                  alert(errorMsg);
              })

          },

          toggleModal(modalId:string, open=true){
            const action:string = open ? 'show':'hide';
            $(`#${modalId}`).modal(action);

          },

          resetPaymentForm(){
            this.payment = {
                  to:'',
                  details:null,
                  amount:null
                };
          },

          /**
           *
           * This method checks the currency of the receipent
           */
          async checkCurrency(){

            if(this.payment.to.length != 10){return;}

            await AppService.checkCurrency(this.payment.to).then(
                (currencyResponse:any)=>{
                  this.currencyData.isChecked = true;
                  let {currency, exchg_rate} = currencyResponse.data;
                  exchg_rate = parseFloat(exchg_rate);
                  const accountCurrency:any = this.account.currency;
                  this.currencyData.mismatch = accountCurrency.name != currency;
                  if(this.currencyData.mismatch){
                    let exchangeFactor = accountCurrency.name  == 'USD' ? exchg_rate : 1/exchg_rate;
                    exchangeFactor = exchangeFactor;
                    this.currencyData.msg = `Receiving account is in ${currency}. Exchg rate: 1${accountCurrency.name} = ${this.formatAmount(exchangeFactor)}${currency}`
                    this.currencyData.exchg_rate = exchangeFactor;
                  }
                }).catch(
                (error:any)=>{
                  alert('Could not validate account holder. Please re-check destination Id!');
              })

          },

          setConversionMsg(){
            const payment:any = {...this.payment};
            if(payment.amount && this.currencyData.mismatch){
              const accountCurrency:any = this.account.currency;
              const recipientCurrency = accountCurrency.name == 'GBP' ?'$':'€';
              this.conversionMsg = `${recipientCurrency}${this.formatAmount(payment.amount * this.currencyData.exchg_rate)}`;
            }

          },

        formatDate(value:string){
            if (value) {
            return moment(String(value)).format('MMM DD, YYYY')
          }
        },

        formatAmount(value:any){
          if (value) {
             return parseFloat(value)
          .toFixed(6)
          .slice(0, -4)
          .replace(/\d(?=(\d{3})+\.)/g, "$&,");
          }
          return 0.0;

        },

         formIsInvalid(){
           const payment:any = {...this.payment};
           let errors = 0;
           for(let key in payment){
             if(!payment[key])
              errors++;
           }
           return !!errors;

       }

       }


});

</script>
