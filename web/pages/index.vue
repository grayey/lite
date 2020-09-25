


<template>

<div>

<div class="modal fade" tabindex="-1" id="accountModal" aria-labelledby="accountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="accountModalLabel">Open Account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <form  v-on:submit.prevent="createAccount">
      <div class="modal-body">

        <div class="form-group">
          <label><b>Username</b></label>
      <input type="text" class="form-control" placeholder="e.g, johnnydepp " v-model="account.userName" required aria-label="Enter UserName" aria-describedby="submit-button">

        </div>

        <div class="form-group">
         <label><b>Currency</b></label>
         <select name="" id="" v-model="account.currency" required class="form-control">
           <option value="">Select account currency</option>
           <option :value="cur.id" v-for="cur in currencies" :key="cur.id">
            {{cur.name}} ({{cur.symbol}})
           </option>

         </select>

        </div>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" :disabled="paying">Save <i class="fa fa-cog fa-spin" v-if="paying"></i></button>
      </div>

       </form>
    </div>
  </div>
</div>



 <div class="overlay"></div>
  <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
    <source src="~/assets/mp4/bg.mp4" type="video/mp4">
  </video>

  <div class="masthead">
    <div class="masthead-bg"></div>
    <div class="container h-100">
      <div class="row h-100">
        <div class="col-12 my-auto">
          <div class="masthead-content text-white py-5 py-md-0">
            <h1 class="mb-3">YBANK</h1>
            <p class="mb-5">Why bank with us?
              <strong>Free money</strong>! Why wait? It's super fast. <button class="btn btn-warning text-primary" @click="toggleModal('accountModal')">Open an account</button> now!</p>
            <form  v-on:submit.prevent="login">
              <div class="input-group input-group-newsletter">
                  <input type="number" class="form-control" placeholder="Enter Account ID" v-model="accountID" required aria-label="Enter Account ID" aria-describedby="submit-button">
                  <div class="input-group-append">
                    <button class="btn btn-secondary"  id="submit-button" type="submit"  :variant="accountID.length == 10 ? 'success' :'primary'" :disabled="accountID.length != 10 || loading">
                      {{loginText}}
                      <i class="fa fa-spinner fa-spin" v-if="loading"></i>

                      </button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="social-icons">
    <ul class="list-unstyled text-center mb-0">
      <li class="list-unstyled-item">
        <a href="#">
          <i class="fab fa-twitter"></i>
        </a>
      </li>
      <li class="list-unstyled-item">
        <a href="#">
          <i class="fab fa-facebook-f"></i>
        </a>
      </li>
      <li class="list-unstyled-item">
        <a href="#">
          <i class="fab fa-instagram"></i>
        </a>
      </li>
    </ul>
  </div>

</div>
</template>


<script lang="ts">

  import Vue from "vue";
  import * as AppService from "../services/appMain.service";
  import * as StorageService  from "../services/storage.service";

  declare const $:any;



  export default Vue.extend({
      data() {
              return {
                  accountID: '',
                  loading:false,
                  paying:false,
                  loginText:'Login',
                  inputError:false,
                  account:{
                    userName:'',
                    currency:''
                  },
                  currencies:[]
              };
          },
          components: {},

          mounted(){
            this.getAllCurrencies();
          },

          methods:{

            /**
             * This method logs an account holder in
             */
            async login(){

              if(this.accountID.length != 10){ // In case the login button is illegally enabled from devtools
                this.inputError = true;
                return alert('Don"t do that!')
              }
              this.inputError = false;
              this.loading = true;
              const loginObject = {account:this.accountID}
              this.loginText = 'Login...'
              await AppService.login(loginObject).then(
                (loginResponse:any) => {

                this.loginText = 'Login';
                const accountToken = loginResponse.data; // Jwt token
                StorageService.setActiveAccount(accountToken); // Encrypt and save token
                window.location.href += `dashboard/transactions`; // Log user in

                }).catch(
                (error:any)=>{
                   this.loginText = 'Login';
                   this.loading = false;
                   const errorMsg:string = error.data ? error.data.message : "An error occurred!";
                   alert(errorMsg);

              })
            },



          /**
            * This method creates an account for a user
             */
            async createAccount(){
              this.paying = true;
              await AppService.createAccount(this.account).then(
                (accountResponse:any) => {
                  // this.paying = false;
                  // this.accountID = accountResponse.data.account_id;
                  // this.login();
                  const accountToken = accountResponse.data; // Jwt token
                  StorageService.setActiveAccount(accountToken); // Encrypt and save token
                  window.location.href += `dashboard/transactions`; // Log user in

                }).catch(
                (error:any)=>{
                   this.paying = false;
                   const errorMsg:string = error.data ? error.data.message : "An error occurred!";
                   alert(errorMsg);

              })
            },




  /**
            * This method creates an account for a user
             */
            async getAllCurrencies(){

              await AppService.getAllCurrencies().then(
                (currenciesResponse:any) => {
                  this.currencies = currenciesResponse.data;
                }).catch(
                (error:any)=>{
                   this.loading = false;
                   const errorMsg:string = error.data ? error.data.message : "Could not load currncies!";
                   alert(errorMsg);

              })
            },




          toggleModal(modalId:string, open=true){
            const action:string = open ? 'show':'hide';
            $(`#${modalId}`).modal(action);

          },

          }
  });

</script>
