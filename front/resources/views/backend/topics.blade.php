@extends('layouts.admin')
@section('pageTitle', 'Topics')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Topics') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-sm mb-4 uppercase font-bold">About</h2>
        <p class="mb-2">When you synchronize from the <a class="text-indigo-400 hover:underline" target="_blank" href="{{ route('dashboard') }}">dashboard</a>, two sets of topics are created (see <a class="text-indigo-400 hover:underline" target="_blank" href="{{ route('method') }}">methodology page</a> for more info). When they are displayed on the <a class="text-indigo-400 hover:underline" target="_blank" href="{{ route('papers') }}">topics</a> page graph, they are presented by their identifier (a number). This page allows you to give them a name to make them more meaningful.</p>
        <p class="mb-10"><span class="font-bold">Important disclaimer:</span> since every topic a regenerated on every synchronization, their name as set in this page will be too.</p>
        <div class="mb-5">
            <a @class([
                    "bg-indigo-200" => $base == 10,
                    "bg-indigo-100",
                    "hover:bg-indigo-200",
                    "rounded-t-lg",
                    "px-5",
                    "py-2.5"
            ]) href="/topics/10">Base 10</a>
            <a @class([
                    "bg-indigo-200" => $base == 20,
                    "bg-indigo-100",
                    "hover:bg-indigo-200",
                    "rounded-t-lg",
                    "px-5",
                    "py-2.5"
            ]) class="bg-indigo-100 hover:bg-indigo-200 rounded-t-lg px-5 py-2.5" href="/topics/20">Base 20</a>
        </div>

        @foreach ($topic_models as $topic)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
            <h3 class="font-medium text-lg p-6 pb-2>
                <span class="text-sm">Topic: {{$topic->cluster_name->name ?? intval(str_replace("base_".$base."_", "", $topic->topic_id))+1}} Base: {{$base}}</span>
                <a class="" href="/topic/{{$topic->topic_id}}">
                    @if(!is_null($topic->cluster_name))
                        <span class="float-right text-indigo-400"> Rename</span>
                    @else
                        <span class="float-right text-indigo-400"> Name</span>
                    @endif
                </a>
            </h3>
            <div class="p-6 pt-2 bg-white">
                @for ($i = 0; $i < 20; $i++)
                    {{ $topic['word_'.$i] }}@if($i < 19), @endif
                @endfor
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
