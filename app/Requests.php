<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
	protected $fillable = [
        'title', 'message', 'status', 'file', 'closed_by', 'client_id', 'is_read',
    ];

    public function closed()
    {
        return $this->belongsTo('App\User', 'closed_by', 'id');
    }
    
    public function client()
    {
        return $this->belongsTo('App\User', 'client_id', 'id');
    }

    // public function users()
    // {
    //     return $this->belongsToMany('App\User'); 
    // }
}
