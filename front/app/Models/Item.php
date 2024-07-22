<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $connection = 'sqlite2';

    public function authors()
    {
        return $this->belongsToMany('App\Models\Author');
    }
}
