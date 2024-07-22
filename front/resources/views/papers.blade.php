@extends('layouts.app')
@section('pageTitle', 'Papers')

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

  <section class="page">
    <div class="page-header clearfix">
      <a href="#" class="showIntro">HELP <i class="bi-question-circle"></i></a>
    </div>

    <div class="text-center">
      <h1 class="page-title inline-title">Papers clustered by topics</h1>
      <select id="select_nb_topics" onchange="selectNbTopics()" name="qty" class="nb_papers_select select">
        <option value="10">10</option>
        <option value="20">20</option>
      </select>
    </div>


    <div id="dots-container" class="dots-container">
      <div id="ldavis_visualisation"></div>
    </div>
    <div id="topic-overlay" class="source-element-slider__overlay"></div>
    <div id="source-element-slider" class="page-papers__slider source-element-slider dragdealer">
      <div class="source-element-slider__inner handle">
          {{-- List all items for the selected cluster --}}
        <ul class="source-element-slider__list">
        </ul>
      </div>
    </div>
  </section>

  <!-- /papers -->

  <div id="tips" class="tips__wrapper">
    <div class="tips">
      <div class="tips__header">
        <h2 class="tips__title">Topics</h2>
        <button id="pop-close" class="pop-close">CLOSE <i class="bi-x-lg"></i></button>
      </div>
      <h3>Tips</h3>
      <p>
      <ol>
        <li>Blue bars represent the 30 most distinctive terms for the selection.
          <img src="img/graphs.svg">
        </li>
        <li>You can browse the most distinctive terms: you will rapidly notice that terms are domain specific (chemistry,
          biology, medecine, anthropology, so on and so forth.).</li>
        <li>Double-tap/click on a cluster to explore it.</li>
        <li>The horizontal list of document is dynamically updated when a cluster is selected.</li>
        <li>Adjust the quantity of cluster (10 by default) with the drop down menu.
          <img src="img/drop-select.svg">
        </li>
      </ol>
      </p>
      <h3>What’s behind ?</h3>
      <p>The topics graph shows a distribution of the content according to the most salient terms.
        This means that the algorithm compares each words in the text space to create clusters of specific words. By doing
        so, we can determine the most uncommon but also recurrent words to classify all the content.</p>
      <p>Mathematically speaking, a unique vector in a multi-dimensional space is calculated for each words and then
        compare to the other. All words that share similar vectors are then grouped in clusters. In order to represent a
        multi-dimensional space in two dimensions, we use a technique that projects the best distribution possible. The X
        and Y axis in this graph does not represent a value but rather a scale of proximity.</p>
    </div>
  </div>
  <!-- /tips -->

  <script>
    var topicNames = @json($clusterNames);
    var topicNames20 = @json($clusterNames20);
  </script>

  <script type="text/javascript">

    function loadJSON(data, callback) {

      var xobj = new XMLHttpRequest();
      xobj.overrideMimeType("application/json");
      xobj.open('GET', data, true); // Replace 'my_data' with the path to your file
      xobj.onreadystatechange = function() {
        if (xobj.readyState == 4 && xobj.status == "200") {
          // Required use of an anonymous callback as .open will NOT return a value but simply returns undefined in asynchronous mode
          callback(xobj.responseText);
        }
      };
      xobj.send(null);
    }

    function LDAvis_load_lib(url, callback) {
      var s = document.createElement('script');
      s.src = url;
      s.async = true;
      s.onreadystatechange = s.onload = callback;
      s.onerror = function() {
        console.warn("failed to load library " + url);
      };
      document.getElementsByTagName("head")[0].appendChild(s);
    }

    if (typeof(LDAvis) !== "undefined") {
      // already loaded: just create the visualization
      ! function(LDAvis) {
        loadData(10);
      }(LDAvis);

    } else {
      // require.js not available: dynamically load d3 & LDAvis
      LDAvis_load_lib("{{ asset('js/d3.v3.min.js') }}", function() {
        LDAvis_load_lib("{{ asset('js/ldavis.js?ver=3') }}", function() {
          loadData(10);
        })
      });
    }

    function loadData(topics_nb) {
      loadJSON("{{ asset('js/data/data_base_') }}" + topics_nb + ".js", function(response) {
        // loadJSON("{{ asset('js/data/data_base_') }}"+topics_nb+"_DEV.js", function(response) {
        // Parse JSON string into object
        var ldavis_visualisation_data = JSON.parse(response);

        new LDAvis("#" + "ldavis_visualisation", ldavis_visualisation_data);
      });
    }

    function selectNbTopics() {
      d = document.getElementById("select_nb_topics").value;
      var elem = document.getElementById('ldavis_visualisation');
      while (elem.firstChild) {
        elem.removeChild(elem.firstChild);
      }
      loadData(d);
    }
  </script>


  @include('layouts.nav')
@endsection
