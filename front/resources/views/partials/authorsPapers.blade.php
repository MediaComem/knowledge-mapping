<div class="source-element-slider__inner handle">
 <ul class="source-element-slider__list">
  <li class="source-element-slider__item">
    <h3 class="source-element-slider__name"><a href="{{url("author/$author->id")}}">{{$author->name}} {{$author->surname}}</a></h3>
    @if($items->count() > 1 && $items->last()->date != $items->first()->date)
      <div class="source-element-slider__active">Active from {{$items->last()->date}} - {{$items->first()->date}}</div>
    @else
      <div class="source-element-slider__active">Active since {{$items->first()->date}}</div>
    @endif
  </li>
  @foreach($items as $item)
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
   <li class="source-element-slider__item">
      <a href="{{url("author/$author->id")}}" class="source-element-slider__more">Show more</a>
      <div class="source-element-slider__qty">{{ $items->total() }} paper{{$items->total() > 1 ? "s" : ""}}</div>
   </li>
 </ul>
</div>
