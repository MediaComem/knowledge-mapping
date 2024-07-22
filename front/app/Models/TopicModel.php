<?php

namespace App\Models;

use App\Models\ClusterName;
use Illuminate\Database\Eloquent\Model;

class TopicModel extends Model
{
    protected $connection = 'sqlite2';
    protected $table = 'topic_models';
    
    public function cluster_name()
    {
        return $this->hasOne(ClusterName::class, 'topic_id', 'topic_id');
    }

}
