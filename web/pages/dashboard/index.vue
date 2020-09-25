<template>

  <div class="container">

   <div class="row mb-5">
     <div class="col-md-4">
        <div class="card text-white bg-info dash-info" >

          <div class="card-body">
            <h4 class="card-title">Premier Account ({{dashboardData.currency.name}})</h4>
            <p class="card-text">{{dashboardData.account_id}}</p>
          </div>
       </div>
     </div>

     <div class="col-md-4">
        <div class="card text-white bg-primary dash-info" >

        <div class="card-body">
          <h4 class="card-title">BALANCE</h4>
          <p class="card-text">{{formatAmount(dashboardData.balance)}}</p>

        </div>

     </div>

     </div>

        <div class="col-md-4">
        <div class="card text-white bg-secondary dash-info" >

        <div class="card-body">
          <h4 class="card-title">Total Transactions</h4>
          <p class="card-text">xxx,yyy.zz</p>

        </div>

     </div>

     </div>
   </div>

   <div class="jumbotron" style="min-height:20rem">




   </div>

  </div>
</template>


<script lang="ts">

import Vue from "vue";
import * as StorageService  from "../../services/storage.service";

 export default Vue.extend({

      layout: 'dashboard',

      data() {
              return {
                  links: [
                    {
                      name:"Transactions",
                      icon: "fa fa-wallet"
                    }


                  ],
                  dashboardData:{
                    account_id:null,
                    balance:null,
                    totalTransactions:null,
                    currency:{}
                  }

              };
          },

          mounted(){

            const accountInfo = {...StorageService.getActiveAccount()};
            accountInfo['totalTransactions'] = null;
            this.dashboardData = accountInfo;

          },

          methods:{
                  formatAmount(value:any){
                        if (value) {
                          return parseFloat(value)
                        .toFixed(6)
                        .slice(0, -4)
                        .replace(/\d(?=(\d{3})+\.)/g, "$&,");
                        }
                        return 0.0;

                      }

       }




 });

</script>
