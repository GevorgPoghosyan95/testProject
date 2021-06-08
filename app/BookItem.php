<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    protected $fillable = ['first_name','user_id','last_name','country_code','timezone_name','phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
