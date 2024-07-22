<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClusterName;
use App\Models\TopicModel;


class DashboardController extends Controller
{

    public function index()
    {
        return view('backend.dashboard');
    }

    public function topicModels($base = 10)
    {
        $topic_models = TopicModel::where('n_topics', $base)->where('type', 'base')->with(['cluster_name'])->get();

        return view('backend.topics')->with([
            'base' => $base,
            'topic_models' => $topic_models
        ]);
    }

    public function editTopic($topic_id)
    {
        $cluster = ClusterName::where('topic_id', $topic_id)->first();
        $topic = TopicModel::where('topic_id', $topic_id)->first();

        return view('backend.editTopic')->with([
            'topic_id' => $topic_id,
            'cluster' => $cluster,
            'topic' => $topic
        ]);
    }

    public function saveTopic($topic_id, Request $request)
    {

        ClusterName::updateOrCreate(
            ['topic_id' => $topic_id],
            ['name' => $request->name]
        );


        return redirect('/topics');
    }
}
