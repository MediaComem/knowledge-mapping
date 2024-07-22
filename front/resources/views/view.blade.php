@extends('layouts.app')
@section('pageTitle', 'View')


@section('meta')
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="{{ $data->title }}" />
  <meta property="og:description" content="{{ $data->abstract }}" />
  <meta property="og:url" content="{{ URL::to('/') }}/view/{{ $data->id }}" />
  <meta property="og:site_name" content="Ayahuasca" />
@endsection

@section('content')

  @include('partials.header')

  <section class="page-article">
    <h1 class="page-title">Selected ressource details</h1>
    <div class="page-search__subtitle">-</div>
    <a class="page-article__back">Back</a>
    <h1 class="page-article__title">{{ $data->title }}</h1>
    <div class="page-article__info">
      <div class="clearfix">
        <div class="document-type">
          <img class="doc-icon"
            src="/img/icons/{{ strtolower(preg_replace('/-+/', '-', preg_replace('/[^\wáéíóú]/', '-', $data->itemType))) }}.svg">
          {{ preg_replace('/(\p{Ll})(\p{Lu})/u', '$1 $2', $data->itemType) }}
        </div>
        <div class="page-article__date">{{ $data->date }}</div>
      </div>
      <div class="page-article__authors">
        @foreach ($authors as $author)
          {{ $author->name }}, {{ $author->surname }}
        @endforeach
      </div>
      <div class="page-article__links">
        @if ($data->DOI)
          DOI: &nbsp;<a href="http://dx.doi.org/{{ $data->DOI }}" class="page-article__link">{{ $data->DOI }}</a>
        @endif
        <a href="#" class="page-article__share">Share</a>
        <ul class="page-article__sharelist share-list">
          <li class="share-list__item">
            <a target="_blank" href="https://www.facebook.com/sharer.php?u={{ URL::to('/') }}/view/{{ $data->id }}"
              class="share-list__link share-list__link--facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="#6473FA"
                  d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
              </svg>
            </a>
          </li>
          <li class="share-list__item">
            <a target="_blank" href="https://twitter.com/home?status={{ URL::to('/') }}/view/{{ $data->id }}">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="#6473FA"
                  d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
              </svg>
            </a>
          </li>
          <li class="share-list__item">
            <a target="_blank"
              href="https://www.linkedin.com/shareArticle?mini=true&url={{ URL::to('/') }}/view/{{ $data->id }}&title={{ $data->title }}&summary=&source=Ayahuasca">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="#6473FA"
                  d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
              </svg>
            </a>
          </li>
        </ul>
      </div>
    </div>
    @if ($data->url)
      <div>
        <br>
        Web link: <a href="{{ parse_url($data->url, PHP_URL_SCHEME) == '' ? 'https://' . $data->url : $data->url }}"
          title="{{ $data->url }}"
          target="_blank">{{ parse_url($data->url, PHP_URL_HOST) }}{{ substr(parse_url($data->url, PHP_URL_PATH), 0, 10) }}...</a>
      </div>
    @endif

    @if ($data->pages)
      <div>
        <br>
        Pages: {{ $data->pages }}
      </div>
    @endif

    @if ($data->date)
    @endif

    @if ($data->has_abstract > 0)
      <h2 class="page-article__subtitle">Abstract</h2>
      <article>
        {{ $data->abstract }}
      </article>
    @endif

  </section>

  @include('layouts.nav')
@endsection
