
import * as apiService from './api.service';


    /**
     *  ---- TRANSACTIONS' SECTION ----
     */


     /**
      * This function returns a list of all transactions [This would be an Admin function]
      */
    // export const getAllTransactions = async () => {
    //   return await apiService.get('transactions')
    //  }



     /**
      *
      * @param accountId
      *
      * This function returns a user's  transactions
      */
    export const getTransactionsByAccountId = async (accountId) =>{
      const path = `accounts/${accountId}/transactions`;
      return await apiService.get(path);
     }



    /**
     *
     * @param {*} accountId
     * @param {*} funds
     * This function handles funds' transfer
     */
    export const  transferFunds = async (accountId,funds) => {
      const path = `accounts/${accountId}/transactions`;
      return await apiService.post(path, funds);
    }


    /**
     *
     * @param {*} recipientId
     * This function checks a recipient's account currency
     */
    export const checkCurrency = async (recipientId)=>{
      const path = `check-currency/${recipientId}`;
      return await apiService.get(path);
    }



     /**
      *
      * ---- USER SECTION ----
      */



     /**
      *
      * @param {*} accountID
      *
      * This method logs a user in
      */
    export const login = async (loginObject) =>{
      return await apiService.post('login',loginObject);
     }

     /**
      *
      * @param {*} accountObject
      * This method creates a new account
      */
     export const createAccount = async (accountObject) =>{
      return await apiService.post('account',accountObject);

     }



     /**
      *
      * @param {*} user
      * This function opens an account for the user
      */
     export const openAccount = async (account) =>{
      return await apiService.post('open-account',account);
     }





     /**
      *
      * This function returns a list of all currencies
      */
      export const getAllCurrencies = async ()=>{
        return await apiService.get('currencies');
      }





