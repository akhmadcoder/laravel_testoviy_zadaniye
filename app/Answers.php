<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    protected $fillable = [
        'request_id', 'message', 'answered_by',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'answered_by', 'id');
    }
    

}
