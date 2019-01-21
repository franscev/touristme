<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
   
protected $table = 'places';

    protected $fillable = [
        'title', 'description', 'startDate', 'endDate', 'coordX', 'coordY'];


    public function places(){
        return $this->hasMany('App\User');
    }
}
