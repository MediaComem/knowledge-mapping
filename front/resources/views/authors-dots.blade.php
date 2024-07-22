@extends('layouts.app')
@section('pageTitle', 'Papers')

@section('content')

  @include('partials.header')

  <section class="page pt-0 clearfix">
    <h1 class="page-title">Authors distributed by publications</h1>
    <br>
    <div class="page-dataset__right page-dots__authors">
      <div class="page-dataset__item full_width">
        <div id="authors_dots" class="page-dataset__dots"></div>
      </div>
    </div>
    <div id="source-element-slider" class="page-papers__slider source-element-slider dragdealer">
      <div class='source-element-slider__empty'>Select an author in the graph</div>
    </div>
  </section>
@endsection
<!-- /papers -->




@include('layouts.nav')
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //TODO Move all this logic to the controller once the python script adapted
    function initDots(data) {
      var authors_itemsCount = {!! json_encode($authors_itemsCount) !!};
      var name, size, radius, y, x;

      var authors = [];

      for (var i = 0; i < data.author_names.length; i++) {
        name = data.author_names[i];

        size = data.author_sizes[i];
        radius = data.radii[i];
        scale = parseInt((clamp(radius, 0.1, 1)) * 10);
        author_infos = authors_itemsCount.find(author => {
          return author.name == name[0] && author.surname == name[1];
        });
        authors.push({
          "firstname": name[0],
          "lastname": name[1],
          "scale": scale,
          "items_count": author_infos ? author_infos.items_count : "?"
        })
      }

      authors.sort(sort_by('lastname', false, function(a) {
        return a.toUpperCase()
      }));

      for (var i = 0; i < authors.length; i++) {
        $("#authors_dots").append("<a href='api/authors/" + authors[i].firstname + "_" + authors[i].lastname +
          "' class='page-dataset__dot show_author tooltip scale_" + authors[i].scale +
          "'><span class='tooltiptext'>" + authors[i].firstname + " " + authors[i].lastname + " (" + authors[i]
          .items_count + ")</span></a>");
      }

      $('a.show_author').bind('click', function(e) {
        e.preventDefault();
        var urlGet = $(this).attr('href');
        loadAuthor(urlGet);
        let name = $(this).attr('href').split('/').pop();
        let updatedUrl = window.location.href.split('?')[0] + "?name=" + name;
        window.history.pushState(null, null, updatedUrl);
        $(this).addClass('selected');
      });
      console.log("in");
    }

    $.getJSON("{{ asset('js/data/authors_data.json') }}", function(data) {
      initDots(data);
    });

    function sort_by(field, reverse, primer) {

      var key = primer ?
        function(x) {
          return primer(x[field])
        } :
        function(x) {
          return x[field]
        };

      reverse = !reverse ? 1 : -1;

      return function(a, b) {
        return a = key(a), b = key(b), reverse * ((a > b) - (b > a));
      }
    }

    function clamp(num, min, max) {
      return num <= min ? min : num >= max ? max : num;
    }

    let name = new URLSearchParams(window.location.search).get('name');
    getAuthor(name);


    function loadAuthor(url) {
      $("#source-element-slider").html('<div class="source-element-slider__empty">Loading . . .</div>');
      // loadedUrl = url;
      $.get(url, function(data) {
        $("#source-element-slider").html(data);

        $(function() {
          new Dragdealer('source-element-slider', {
            // x: 1,
            vertical: false,
            loose: true,
            slide: true,
            // disabled: true,
            speed: 0.15,
            callback: function(x, y) {
              // Only 0 and 1 are the possible values because of "steps: 2"
              $('#source-element-slider').css({
                'bottom': '0',
                'transition': 'all 0.35s linear'
              });
              $('#topic-overlay').css({
                'display': 'block'
              });
            }
          });
        })

      });
    }

    function getAuthor(name) {
      var urlGet = "api/authors";
      if (name) {
        urlGet = "api/authors/" + name;
      }

      // if(urlGet != loadedUrl){
      $("#source-element-slider").html('<div class="source-element-slider__empty">Loading...</div>');
      loadAuthor(urlGet);
      // }
    }

    window.addEventListener("pageshow", function(event) {
      // var historyTraversal = event.persisted ||
      //                        ( typeof window.performance != "undefined" &&
      //                             window.performance.navigation.type === 2 );
      // if ( historyTraversal ) {
      //   // Handle page restore.
      var _wr = function(type) {
        var orig = history[type];
        return function() {
          var rv = orig.apply(this, arguments);
          var e = new Event(type);
          e.arguments = arguments;
          window.dispatchEvent(e);
          return rv;
        };
      };
      history.pushState = _wr('pushState'), history.replaceState = _wr('replaceState');
      let name = new URLSearchParams(window.location.search).get('name');
        getAuthor(name);
      // }
    });


    //* Still usefull ?
    if (document.body.clientWidth <= 1025) {
      window.onscroll = function() {
        stickAuthors()
      };
    }

    function stickAuthors() {
      if (document.body.scrollTop > 70 || document.documentElement.scrollTop > 70) {
        $("#source-element-slider").addClass("sticky");
      } else {
        $("#source-element-slider").removeClass("sticky");
      }
    }



  });
</script>
