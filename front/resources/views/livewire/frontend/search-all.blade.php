<div>
    @if($queryContent)
        <h1 class="page-title">Search – Results for <i>{{$queryContent}}</i></h1>
    @else
        <h1 class="page-title">Search through the entire dataset</h1>
    @endif
    <livewire:component.search-panel queryDateMin="{{$queryDateMin}}" queryDateMax="{{$queryDateMax}}"/>
    <div wire:loading.inline class="page-search__loading">
        <div class="page-search__loading-spinner" title="loading content">
        </div><br>
        <p> Note: short search content may be longer to process...</p>
    </div>
    @if($queryContent && (count($authors) || count($papers) || count($abstracts)))
    <div wire:loading.remove class="page-search__results">
        <h2 class="page_search__date-window"><i>Results date window: {{$queryDateMin}}-{{$queryDateMax}}</i></h2>
        @if(count($authors))
        <div class="page-search__authors search-authors">
            <div class="search-authors__title">Authors ({{count($authors)}})</div>
                @foreach($authors->take(3) as $author)
                    <div class="search-authors__item">
                        <h2 class="search-authors__author">
                            <a href="/author/{{$author['id']}}" class="search-results-item">{{$author['name']}} {{$author['surname']}}</a>
                        </h2>
                        <div class="search-authors__info">
                            <div class="search-authors__years">Activity:
                                @if($author['items_min_year'] === $author['items_max_year'])
                                    {{$author['items_min_year']}}
                                @else
                                    {{$author['items_min_year']}} - {{$author['items_max_year']}}
                                @endif
                            </div>
                            <div class="search-authors__qty">{{ $author['items_count'] }}
                                @if($author['items_count'] == 1)
                                    document
                                @else
                                    documents
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- /authors item -->
                @if(count($authors) > 3)
                    <div class="page-search__more">
                        <a href="/search/authors?q={{$queryContent}}&dateFrom={{$queryDateFrom}}&dateTo={{$queryDateTo}}" class="page-search__morelink"><img src="img/arrow-right-blue.svg" alt="See All"> See All</a>
                        <span class="page-search__moreqty">({{(count($authors)-3)}} more)</span>
                    </div>
                @endif
        </div>
    @endif
      <!-- /authors search -->
    @if(count($papers))
        <div class="page-search__papers search-papers">
            <div class="search-papers__title">Paper titles ({{count($papers)}})</div>
            @foreach($papers->take(3) as $item)
                <div class="search-papers__item">
                    <h2 class="search-papers__name">
                    <a href="/view/{{$item['id']}}" class="search-results-item">{{$item['title']}}</a>
                    </h2>
                    <span class="search-papers__year">{{$item['date']}}</span>
                    <span class="search-papers__authors">
                    @foreach($item['authors'] as $it)
                        {{$it['name']}}, {{$it['surname']}}
                    @endforeach
                    </span>
                </div>
                <!-- /papers item -->
            @endforeach
            @if(count($papers) > 3)
                <div class="page-search__more">
                    <a href="/search/papers?q={{$queryContent}}&dateFrom={{$queryDateFrom}}&dateTo={{$queryDateTo}}" class="page-search__morelink"><img src="img/arrow-right-blue.svg" alt="See All"> See All</a>
                    <span class="page-search__moreqty">({{(count($papers)-3)}} more)</span>
                </div>
            @endif
        </div>
    @endif
    <!-- /papers search -->

    @if(count($abstracts))
        <div class="page-search__abstract search-abstract">
            <div class="search-abstract__title">Abstract ({{count($abstracts)}})</div>
            @foreach($abstracts->take(3) as $item)
                <div class="search-abstract__item">
                    <h2 class="search-abstract__name">
                        <a href="/view/{{$item['id']}}" class="search-results-item">{{$item['abstract']}}</a>
                    </h2>
                    <h2 class="text-overflow search-papers__name">
                        <a href="/view/{{$item['id']}}">{{$item['title']}}</a>
                    </h2>
                </div>
                <!-- /abstract item -->
            @endforeach
            @if(count($abstracts) > 3)
                <div class="page-search__more">
                    <a href="/search/abstract?q={{$queryContent}}&dateFrom={{$queryDateFrom}}&dateTo={{$queryDateTo}}" class="page-search__morelink"><img src="img/arrow-right-blue.svg" alt="See All"> See All</a>
                    <span class="page-search__moreqty">({{(count($abstracts)-3)}} more)</span>
                </div>
            @endif
        </div>
        <!-- /abstract search -->
    </div>
    @endif
    @elseif($queryContent)
    <p class="page-search__no-result">Aucun résultat</p>
    @endif
</div>
