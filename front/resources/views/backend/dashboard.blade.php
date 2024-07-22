@extends('layouts.admin')
@section('pageTitle', 'Author')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Dashboard') }}
</h2>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <livewire:backend.sync-panel />
    </div>
</div>
@endsection
