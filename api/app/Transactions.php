<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Accounts;

class Transactions extends Model
{
    //

    protected $guarded = [];

    public function accounts(){

      return $this->belongsTo(Accounts::class);
    }


}
