<div>
    @if($queryContent)
        <h1 class="page-title">Search Papers – Results for <i>{{$queryContent}}</i></h1>
    @else
        <h1 class="page-title">Search Papers</h1>
    @endif
    <livewire:component.search-panel queryDateMin="{{$queryDateMin}}" queryDateMax="{{$queryDateMax}}"/>
    <div wire:loading.inline class="page-search__loading">
        <div class="page-search__loading-spinner" title="loading content">
        </div><br>
        <p> Note: short search content may be longer to process...</p>
    </div>
    @if($queryContent && (count($papers)) || $queryTopicId && $queryTopicBase)
    <div wire:loading.remove class="page-search__results">
        <h2 class="page_search__date-window"><i>Results date window: {{$queryDateMin}}-{{$queryDateMax}}</i></h2>
        @if(count($papers))
        <div class="page-search__papers search-papers">
            <div class="search-papers__title">Paper titles ({{$papers->total()}})</div>
            @foreach($papers as $item)
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
            @if($papers->hasPages())
                <div class="page-search__pagination">
                    @if($papers->currentPage() != 1)
                    <button wire:click="navigatePreviousPage()">« Previous</button>
                    @endif
                    @if($papers->currentPage() != $papers->lastPage())
                    <button wire:click="navigateNextPage()">Next »</button>
                    @endif
                    <p>page {{$papers->currentPage()}} of {{$papers->lastPage()}}</p>
                </div>
            @endif
        </div>
    @endif
    @elseif($queryContent)
    <p class="page-search__no-result">Aucun résultat</p>
    @endif
</div>
