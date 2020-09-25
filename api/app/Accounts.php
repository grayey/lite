<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Transactions;

class Accounts extends Model
{
    //

    protected $guarded = [];

    public function transactions(){

      return $this->hasMany(Transactions::class);
    }


    public function currency(){
      return $this->belongsTo(Currency::class);
    }







}
