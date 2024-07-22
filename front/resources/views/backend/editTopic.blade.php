@extends('layouts.admin')
@section('pageTitle', 'Topics')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Topic') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg mb-2">
            <form action="/topic/{{$topic_id}}" method="post">
            @csrf
            <div class="flex">
                <input class="w-1/2 block rounded-lg border-black-200 mr-2" type="text" name="name" value="{{ $cluster->name ?? '' }}">
                <input class="bg-indigo-400 hover:bg-indigo-500 text-white p-3 cursor-not-allowed focus:ring-4 focus:ring-indigo-300 rounded-lg px-5 py-2.5 focus:outline-none" type="submit" value="save">
            </div>
            </form>
            <div class=" mt-10 text-lg">
                @for ($i = 0; $i < 20; $i++)
                    {{ $topic['word_'.$i]}}@if($i < 19), @endif
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
