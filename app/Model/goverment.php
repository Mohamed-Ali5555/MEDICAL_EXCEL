<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class goverment extends Model
{
   
    protected $table = "goverments";

    protected $fillable = ['name', 'status'];
    public function city(){
        // return $this->hasMany('App\Models\Employeer')->where('section_id','id');
        return $this->hasMany(city::Class,'goverment_id','id');

    }
}
