<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class city extends Model
{
    protected $table = "city";

    protected $fillable = ['name', 'status','goverment_id'];

    public function goverment(){
        return $this->belongsTo(goverment::Class,'goverment_id','id');

    }
}
