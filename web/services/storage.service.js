
import * as EncryptionService from "./encryption.service";

/**
 *
 * @param {*} account
 *
 * This function sets the user session on the browser
 */
export const setActiveAccount = (token)=>{
  const accountToken = encryptAccountToken(token);
  localStorage.setItem('ACTIVE_ACC', accountToken)

}



/**
 *
 * This function retrieves the stored session from the browser
 */
export const getActiveAccount = () =>{
  return decryptAccountToken();

}


/**
 * This function clears the session
 */
export const clearActiveAccount = ()=>{
  if(localStorage){
    localStorage.setItem('ACTIVE_ACC', null);
    localStorage.clear();
    return;
  }

}


/**
 * This method decrypts an account's token
 */
export const decryptAccountToken = () => {

  let  token = localStorage.getItem('ACTIVE_ACC');
  token = EncryptionService.decryptData(token);
  const accountToken = token  ? JSON.parse(token) : null;
  return accountToken;
}


export const encryptAccountToken = (token) =>{
  let accountToken = JSON.stringify(token); // encrypt token //
  accountToken = EncryptionService.encryptData(accountToken);
  return accountToken;

}





