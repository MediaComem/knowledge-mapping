@extends('layouts.app')
@section('pageTitle', 'Author')

@section('content')

  @include('partials.header')

  <section class="page-authors">
    <h1 class="page-title">Selected author details</h1>
    <div class="page-search__subtitle">-</div>
    <a class="page-article__back">Back</a>
    <h2 class="author-name page-search__subtitle">{{$author->name}} {{$author->surname}}</h2>
    <h3>{{count($author->items)}} publication{{count($author->items) > 1 ? "s" : ""}}</h3>
    <!-- /author -->

  <div class="page-search__papers">
    <div class="search-papers__title author-papers-title">Paper titles: {{count($author->items)}}</div>
    <ul class="source-element-slider__list">
        @foreach($author->items as $item)
        <li class="source-element-slider__item">
            <h3 class="source-element-slider__title"><a href="/view/{{$item->id}}">{{$item->title}}</a></h3>
            <div class="source-element-slider__secondary-info">{{$item->date}}</div>
            <div class="source-element-slider__primary-info">
                @foreach ($item->authors as $author)
                {{$author->name}} {{$author->surname}} @if(!$loop->last), @endif
                @endforeach
            </div>
        </li>
        @endforeach
        <li class="source-element-slider__item"></li>
    </ul>
  </div>
  </section>

@include('layouts.nav')
@endsection
