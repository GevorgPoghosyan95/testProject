<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookItem extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
