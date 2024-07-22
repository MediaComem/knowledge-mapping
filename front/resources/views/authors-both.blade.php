@extends('layouts.app')
@section('pageTitle', 'Papers')

@section('content')

  @include('partials.header')

  <section class="page-authors">
    <h1 class="page-authors__title">Ayahuasca - Knowledge Mapping</h1>
    <div class="page-authors__soon">Authors</div>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
<script type="text/javascript">
  $(document).ready(function() {

    function initDots(data) {
      console.log(data);
      var name, size, radius, y, x;

      for(var i = 0; i < data.author_names.length; i++) {
          name = data.author_names[i];
          size = data.author_sizes[i];
          radius = data.radii[i];

          y = data.y.__ndarray__;
          x = data.y.__ndarray__;

          $("#authors_dots").append("<a href='api/authors/"+name[0]+"+"+name[1]+"' class='page-dataset__dot show_author tooltip'><span class='tooltiptext'>"+radius+"</span></a>");
      }

      $('a.show_author').bind('click', function (e) {
          e.preventDefault();
          var urlGet = $(this).attr('href');
          loadAuthor(urlGet);
      });
    }

    $.getJSON("{{ asset('js/data/authors_data.json') }}", function(data) {
      initDots(data.data);
    });


    var loadedUrl = "";

    function loadAuthor(url) {
        $( "#source-element-slider" ).html('<div class="source-element-slider__empty">Loading . . .</div>');
        // loadedUrl = url;
        $.get( url, function( data ) {
          $( "#source-element-slider" ).html( data );

          $(function () {
            new Dragdealer('source-element-slider', {
              // x: 1,
              vertical: false,
              loose: true,
              slide: true,
              // disabled: true,
              speed: 0.15,
              callback: function (x, y) {
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
        if(name){
          urlGet = "api/authors/"+name;
        }

        // if(urlGet != loadedUrl){
            $( "#source-element-slider" ).html('<div class="source-element-slider__empty">Loading...</div>');
            loadAuthor(urlGet);
        // }
    }

    window.addEventListener( "pageshow", function ( event ) {
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
        getAuthor();
      // }
    });


    if(document.body.clientWidth <= 1025){
        window.onscroll = function() {stickAuthors()};
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
