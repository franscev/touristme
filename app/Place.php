<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
   
protected $table = 'places';

    protected $fillable = [
        'title', 'description', 'startDate', 'endDate', 'coordX', 'coordY', 'user_id'];


    public function user(){
        return $this->belongsTo('App\User');
    }
}
