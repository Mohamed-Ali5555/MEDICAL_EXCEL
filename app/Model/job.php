<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class job extends Model
{
    protected $table = "job";

    protected $fillable = ['name', 'status'];

    public function customer(){
        // return $this->hasMany('App\Models\Employeer')->where('section_id','id');
        return $this->hasMany(job::Class,'jobAs_id','id');

    }
}
