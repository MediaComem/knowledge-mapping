<?php

namespace App\Models;

use App\Models\TopicModel;
use Illuminate\Database\Eloquent\Model;

class ClusterName extends Model
{
    protected $connection = 'sqlite';
    protected $table = 'cluster_names';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'topic_id',
    ];

    public function cluster()
    {
        return $this->belongsTo(TopicModel::class, 'topic_id', 'topic_id');
    }
    

}
