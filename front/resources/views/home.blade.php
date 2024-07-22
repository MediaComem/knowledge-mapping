@extends('layouts.app')
@section('pageTitle', 'Welcome')

@section('content')

  <header class="main-header">
    <span class="main-header__left"></span>
    <span id="js-menu-button" class="menu-button">
      <button id="menu-toggle" class="menu-button__menu">Menu</button>
      <button id="menu-close" class="menu-button__close">Close</button>
    </span>
    <div id="back-menu-overlay"></div>
  </header>
  <!-- /header -->

  <section class="page pt-0">
    <h1 class="page-title">Knowledge Mapping</h1>
    <div class="page-main__lead">
      An experimental tool using AI to explore a curated selection of scientific literature paper.
    </div>
    <h2 class="page-main__explore">Explore dataset by</h2>
    <div class="page-main__select">
      <a href="/papers" class="page-main__button">
        <img src="/img/Papers.svg" alt="Papers" class="page-main__icon">
        <div>
          <b>Papers</b><br>
          Clustered by topics
        </div>
      </a>
      <a href="/authors" class="page-main__button">
        <img src="/img/Authors.svg" alt="Authors" class="page-main__icon">
        <div>
          <b>Authors</b><br>
          Distributed by publications
        </div>
      </a>
    </div>
    <h2>Current dataset ressources ({{ count($data) }})</h2>
    <div class="page-dataset">
      <div class="page-dataset__left">
        <div class="page-dataset__item">
          <h3 class="page-dataset__title">
            <img class="doc-icon" src="/img/icons/paper.svg">
            @php
              print preg_replace('/(\p{Ll})(\p{Lu})/u', '$1 $2', $categories[0]->itemType);
            @endphp
            ({{ $categories[0]->total }})
          </h3>
          <div class="page-dataset__dots">
            @foreach ($data as $item)
              @if ($item->itemType == $categories[0]->itemType)
                <a href="view/{{ $item->id }}" title="{{ $item->title }}" class="page-dataset__dot tooltip">
                  <span class="tooltiptext large">
                    <b>{{ substr($item->title, 0, 60) . '...' }}</b>
                    <br>
                    {{ $item->year }}
                    <br>
                    @foreach ($item->authors as $auth)
                      <span>
                        @if ($loop->index < 5)
                          {{ $auth->surname }}@if (!$loop->last)
                            ,
                          @endif
                        @endif
                      </span>
                    @endforeach
                    @if (count($item->authors) > 5)
                      &hellip;
                    @endif
                  </span>
                </a>
              @endif
            @endforeach
          </div>
        </div>
      </div>
      <div class="page-dataset__right">

        @foreach ($categories as $key => $item)
          @if ($key > 0)
            <div class="page-dataset__item">
              <h3 class="page-dataset__title"><img class="doc-icon"
                  src="/img/icons/{{ strtolower(preg_replace('/-+/', '-', preg_replace('/[^\wáéíóú]/', '-', $item->itemType))) }}.svg"
                  onerror="this.onerror=null;this.src='img/icons/default.svg';">
                  @php
                    print preg_replace('/(\p{Ll})(\p{Lu})/u', '$1 $2', $item->itemType);
                  @endphp
                ({{ $item->total }})</h3>
              @if ($item->total > 14)
                <div class="page-dataset__dots">
                  @foreach ($data as $i)
                    @if ($i->itemType == $item->itemType)
                      <a href="view/{{ $i->id }}" title="{{ $i->title }}" class="page-dataset__dot tooltip">
                        <span class="tooltiptext large">
                          <b>{{ substr($i->title, 0, 60) . '...' }}</b>
                          <br>
                          {{ $i->year }}
                          <br>
                          @foreach ($i->authors as $auth)
                            <span>
                              @if ($loop->index < 5)
                                {{ $auth->surname }}@if (!$loop->last)
                                  ,
                                @endif
                              @endif
                            </span>
                          @endforeach
                          @if (count($i->authors) > 5)
                            &hellip;
                          @endif
                        </span>
                      </a>
                    @endif
                  @endforeach
                </div>
              @else
                <div class="page-dataset__dots page-dataset__dots--inline">
                  @foreach ($data as $i)
                    @if ($i->itemType == $item->itemType)
                      <a href="view/{{ $i->id }}" title="{{ $i->title }}" class="page-dataset__dot tooltip">
                        <span class="tooltiptext large">
                          <b>{{ substr($i->title, 0, 60) . '...' }}</b>
                          <br>
                          {{ $i->year }}
                          <br>
                          @foreach ($i->authors as $auth)
                            <span>
                              @if ($loop->index < 5)
                                {{ $auth->surname }}@if (!$loop->last)
                                  ,
                                @endif
                              @endif
                            </span>
                          @endforeach
                          @if (count($i->authors) > 5)
                            &hellip;
                          @endif
                        </span>
                      </a>
                    @endif
                  @endforeach
                </div>
              @endif
            </div>
          @endif
        @endforeach

      </div>
    </div>

    <div>
      <h2 class="page-main__about">About the project</h2>
      <div class="footer-links">
        <a href="/introduction" class="main-links__link">Introduction</a>
        <a href="/method" class="main-links__link">Method</a>
        <a href="/credits" class="main-links__link">Credits</a>
      </div>
    </div>
  </section>
  <!-- /page-main -->

  @include('layouts.nav')
@endsection
