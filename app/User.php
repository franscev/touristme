<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
   
   protected $table = 'users';

    protected $fillable = [
        'name', 'email', 'password', 'passwordConfirm', 'role_id'];


    public function places(){
        return $this->hasMany('App\Place');
    }
}
