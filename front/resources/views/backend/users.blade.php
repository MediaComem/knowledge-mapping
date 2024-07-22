@extends('layouts.admin')
@section('pageTitle', 'Author')

@section('header')
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    {{ __('Dashboard') }}
</h2>
@endsection

@section('content')
@if(session('success'))
    <x-success-notification :message="session('success')"/>
@endif
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <x-users-list :users="$users"/>
    </div>
</div>
<script>
    const notification = document.getElementById('notification');
    if(notification) {
        setTimeout(() => {
            notification.classList.add('opacity-0');
        }, 3000);
    }
</script>
@endsection
