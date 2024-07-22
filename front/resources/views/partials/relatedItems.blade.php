<div class="source-element-slider__inner handle">
  <ul class="source-element-slider__list">
    @foreach ($items as $item)
      <li <?php if($loop->first) { ?>id='first-papers-result'<?php } ?> class="source-element-slider__item">
        <h3 class="source-element-slider__title"><a href="/view/{{ $item->id }}">{{ $item->title }}</a></h3>
        <div class="source-element-slider__secondary-info">{{ $item->date }}</div>
        <div class="source-element-slider__primary-info">
          @foreach ($item->authors as $author)
            {{ $author->name }}, {{ $author->surname }}
          @endforeach
        </div>
      </li>
    @endforeach
    <li class="source-element-slider__item">
      @if ($term && $topic < 1)
        <a href="{{ url('search/papers?q=' . $term) }}" class="source-element-slider__more">View all</a>
      @elseif($term && $topic > 0 && $base)
        <a href="{{ url('search/papers?q=' . $term . '&topicId=' . $topic . '&base=' . $base) }}"
          class="source-element-slider__more">View all</a>
      @elseif($topic > 0 && $base)
        <a href="{{ url('search/papers?topicId=' . $topic . '&base=' . $base) }}" class="source-element-slider__more">View
          all</a>
      @endif
      <div class="source-element-slider__qty">
        {{ $items->total() }} papers
        @if ($topic >= 1)
          on topic <strong>{{ $topic }}</strong>
        @endif
        @if ($term)
          featuring <strong>"{{ $term }}"</strong>
        @endif
      </div>
    </li>
  </ul>
  <div class="page-pagination">
    <ul class="pagination">
      @if ($items->previousPageUrl())
        <li class="page-item">
          <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="next">« Previous</a>
        </li>
      @endif
      @if ($items->nextPageUrl())
        <li class="page-item">
          <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">Next »</a>
        </li>
      @endif
    </ul>
  </div>
</div>
