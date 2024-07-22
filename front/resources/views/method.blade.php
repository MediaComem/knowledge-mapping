@extends('layouts.app')
@section('pageTitle', 'Method')

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
    <h1 class="page-static__title">Method</h1>
    <div class="page-static__content">
      <div class="method">
        <h2 class="method__title">Published Papers</h2>
        <img class="method__page" src="img/Page 1.png" alt="">
        <img class="method__arrow" src="img/block-arrow.svg" alt="">
        <h3>Extraction of content</h3>
        <div class="method__lead">In bold = content used for mapping</div>
        <img class="method__extract" src="img/extract.svg " alt="">
        <img class="method__arrow" src="img/block-arrow.svg" alt="">
        <table class="method__space">
          <tr>
            <td>
              <img src="img/network.png" alt="">
            </td>
            <td>
              <strong>Document space:</strong><br>
              <p>Each document is placed in a multi-dimensional space according to the distribution of words within it.</p>
              <p>Each document has a unique signature, according to the frequency of every word appearing in it.</p>
            </td>
          </tr>
        </table>
        <img class="method__arrow" src="img/block-arrow.svg" alt="">
        <div class="method__algo">
          <img src="img/algo.png" alt="">
          <span>Algorithmic representations of the document space.</span>
        </div>
        <img class="method__arrow" src="img/block-arrow.svg" alt="">
      </div>
      <br>
      <p><strong>1. Creation of document topics:</strong><br>
      Based on patterns of word distributions in documents, words can be grouped into clusters of similar meaning, resulting into coherent topics such as “chemistry” or “shamanism”. Each document can then be allocated to one or more topics according to its contents.
      </p>
      <p><strong>2. The role of words within document and author topics.</strong><br>
      Every topic is represented by the most important words characterizing it. Given that a word can influence multiple topics, we can either explore topics by words, or explore words by their role within topics.
      </p>
      <p>In this project, we have divided the words space into 10 and 20 topics, creating 2 topic models.</p>
      <hr>
      <h3>Development Roadmap</h3>
      <p><strong>1. Creation of author topics. </strong><br>
      Authors allocation into topics by grouping their publications according to word clusters. As a result, authors will be distinguished and grouped according to the variety of contributions they have authored.
      </p>
      <p>The aim is to create a map of authors showing their respective inclinations.</p>
      <p><strong>2. Locations and time.</strong><br>
      Using geographical location and date as <strong>filters</strong> on the above mapping, we could visualize historical aspects of the scientific literature.
      </p>
      <p>For example, how topic interests evolved through time, at what pace the literature has grown, who are the recurring authors, what are the countries which produced most contributions about Ayahuasca, etc.</p>
      <p>Playing with time and space cursors can lead to many new global insights about the evolution of the scientific literature.</p>
    </div>
  </section>
  <!-- /page-main -->

@include('layouts.nav')
@endsection
