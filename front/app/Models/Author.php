<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $connection = 'sqlite2';
    
    public function items()
    {
        // return $this->belongsToMany('App\Item');
        return $this->belongsToMany('App\Models\Item');
    }

    public function firstItem() {
        return $this->items()->first();
    }
}
