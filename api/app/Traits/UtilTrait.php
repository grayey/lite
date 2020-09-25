<?php

namespace App\Traits;
use Illuminate\Support\Str;


trait UtilTrait{

/**
 * [generateTransactionId description]
 * @return [type] [string]
 * This method returns a unique string for transaction ID
 */

  public static function generateTransactionId(){
    $time = now();
    $time = str_replace(' ','',$time);
    $time = str_replace(':','',$time);
    return Str::random(10).'-'.$time;
  }



}
