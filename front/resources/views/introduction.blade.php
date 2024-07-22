@extends('layouts.app')
@section('pageTitle', 'Introduction')

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

  <section class="page-static">
    <h1 class="page-static__title">Introduction</h1>
    <div class="page-static__content">
      <p>The Knowledge Mapping web tool aims to provide an experimental tool to get access to a large and extending scientific knowledge from a specific domain.</p>
      <p>By using a range of machine learning algorithms - topic modeling, text mining and entities recognition to name a few - the platform proposes an innovative way to explore a very large and curated collection of academic papers hosted on <a href="https://www.zotero.org/" target="_blank">Zotero</a>.</p>
      <p>Initiated by Prof. Laurent Rivier and the designer Laurent Bolli, the current version of the platform is its first stage of development.</p>
      <p><strong>What you will find:</strong><br>
      - published (peer reviewed) scientific articles covering a large range of academic fields
      </p>
      <p><strong>What you won’t find:</strong><br>
      - testimonials (the next version should include it)<br>
      - artworks<br>
      - social media sources<br>
      - unidentified / non-scientific sources<br>
      </p>
      <br>
      <h2 class="page-static__title">Initial purpose and ambition of the project</h2>
      <p><strong>1. Historical context</strong><br>
      The initial aim of the project was to create a platform showcasing Professor Laurent Rivier's research into Ayahuasca. Using the latest technologies to automatically analyse a vast quantity of texts, the platform would cover all aspects of this fascinating drink, enabling visitors to explore and educate themselves in depth through selected original sources. Over time, it was decided to make it a more general tool, accessible to anyone wishing to share their knowledge, whatever the field (academic, industrial research, etc.).
      </p>
      <p><strong>2. Goal</strong><br>
      Knowledge Mapping is designed to offer a new way of interacting with a large collection of scientific papers. When tackling a new topic, it's always difficult to highlight the main themes, the links between them and the most prolific authors. This is precisely what the tool aims to do.
      </p>
    </div>
  </section>
  <!-- /page-main -->

@include('layouts.nav')
@endsection
