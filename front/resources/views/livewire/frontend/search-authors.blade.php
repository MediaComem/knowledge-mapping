<div>
    @if($queryContent)
        <h1 class="page-title">Search Authors – Results for <i>{{$queryContent}}</i></h1>
    @else
        <h1 class="page-title">Search Authors</h1>
    @endif
    <livewire:component.search-panel queryDateMin="{{$queryDateMin}}" queryDateMax="{{$queryDateMax}}"/>
    <div wire:loading.inline class="page-search__loading">
        <div class="page-search__loading-spinner" title="loading content">
        </div><br>
        <p> Note: short search content may be longer to process...</p>
    </div>
    @if($queryContent && (count($authors)))
    <div wire:loading.remove class="page-search__results">
        <h2 class="page_search__date-window"><i>Results date window: {{$queryDateMin}}-{{$queryDateMax}}</i></h2>
        @if(count($authors))
        <div class="page-search__authors search-authors">
            <div class="search-authors__title">Authors ({{$authors->total()}})</div>
                @foreach($authors as $author)
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
                @if($authors->hasPages())
                <div class="page-search__pagination">
                    @if($authors->currentPage() != 1)
                    <button wire:click="navigatePreviousPage()">« Previous</button>
                    @endif
                    @if($authors->currentPage() != $authors->lastPage())
                    <button wire:click="navigateNextPage()">Next »</button>
                    @endif
                    <p>page {{$authors->currentPage()}} of {{$authors->lastPage()}}</p>
                </div>
                @endif

        </div>
    @endif
    @elseif($queryContent)
    <p class="page-search__no-result">Aucun résultat</p>
    @endif
</div>
