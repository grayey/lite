import * as CryptoJS from "crypto-js";

/**
 *
 * @param {*} data
 *
 * This method is used to encrypt tokens
 */
export const encryptData = (data) =>{
  const encryptionKey = process.env.ENCRYPTION_KEY;
  const ciphertext = CryptoJS.AES.encrypt(JSON.stringify(data), encryptionKey).toString();
  return ciphertext;
}


/**
 *
 * @param {*} data
 *
 * This function is used to decrypt tokens
 */
export const decryptData = (ciphertext)=>{
  const decryptionKey = process.env.ENCRYPTION_KEY;
  const bytes  = CryptoJS.AES.decrypt(ciphertext, decryptionKey);
  const decryptedData = JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
  return decryptedData;

}
