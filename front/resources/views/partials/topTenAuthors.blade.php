<div class="source-element-slider__inner handle">
    <ul class="source-element-slider__list">
        @foreach($topAuthors as $author)
            <li class="source-element-slider__item">
                <h3 class="source-element-slider__title source-element-slider__author-title"><a href="{{url("author/$author->id")}}">{{$author->name}} {{$author->surname}}</a></h3>
                <div class="source-element-slider__primary-info">
                    {{$author->items_count}} publications
                </div>
                <div class="source-element-slider__secondary-info">from {{$author->items_min_year}} to {{$author->items_max_year}}</div>
            </li>
        @endforeach
        <li class="source-element-slider__item">
            <h2>Top ten authors from current dataset</h2>
        </li>

    </ul>
</div>
