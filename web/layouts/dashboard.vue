
<template>
  <div>
 <header>
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">YBANK</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <!-- <li class="nav-item active">
              <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Dropdown
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li> -->
          </ul>
          <form class="form-inline my-2 my-lg-0">
            <!-- <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search"> -->
            <button class="btn btn-outline-danger my-2 my-sm-0" type="button" @click = logOut()>Logout</button>
          </form>
        </div>
      </nav>
 </header>


 <div class="sidebar">

    <ul class="links">
      <li class="text-dark">
       <b> Welcome, {{account.name}} </b> <span class="dot"></span>
      </li>
        <li v-for="link in links" :key="link.url">
          <NuxtLink :to="'/'+link.url">
           <span class=""> {{ link.name }} </span> <i :class="link.icon"></i>
          </NuxtLink>
        </li>
      </ul>


  </div>


  <div class="content">
    <nuxt />
  </div>

  </div>
</template>


<script lang="ts">

import Vue from "vue";
import * as StorageService  from "../services/storage.service";


 export default Vue.extend({


      data() {
              return {
                  links: [

                     {
                      name:"Dashboard",
                      icon: "fa fa-home",
                      url:"dashboard"
                    },



                     {
                      name:"Transactions",
                      icon: "fa fa-wallet",
                      url:"dashboard/transactions"

                    }


                  ],

                  account:{}

              };
          },

          mounted(){
            this.account = StorageService.getActiveAccount();
            if(!this.account){
              window.location.href = '/';
            }
          },

          methods:{

            logOut: () => {
              window.location.href = '/';
              StorageService.clearActiveAccount();
              // this.$router.push('/');
            }
          }



 });

</script>
