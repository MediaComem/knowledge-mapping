@extends('layouts.app')
@section('pageTitle', 'Search')

@section('content')
    @include('partials.header')
    <section class="page pt-0">
        <div class="inner-section">
            <section class="page-search">
                <livewire:frontend.search-abstracts />
            </section>
        </div>
    </section>
@include('layouts.nav')
@endsection
