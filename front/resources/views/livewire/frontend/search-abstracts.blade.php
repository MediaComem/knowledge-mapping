<div>
    @if($queryContent)
        <h1 class="page-title">Search Abstracts – Results for <i>{{$queryContent}}</i></h1>
    @else
        <h1 class="page-title">Search Abstracts</h1>
    @endif
    <livewire:component.search-panel queryDateMin="{{$queryDateMin}}" queryDateMax="{{$queryDateMax}}"/>
    <div wire:loading.inline class="page-search__loading">
        <div class="page-search__loading-spinner" title="loading content">
        </div><br>
        <p> Note: short search content may be longer to process...</p>
    </div>
    @if($queryContent && (count($abstracts)))
    <div wire:loading.remove class="page-search__results">
        <h2 class="page_search__date-window"><i>Results date window: {{$queryDateMin}}-{{$queryDateMax}}</i></h2>
        @if(count($abstracts))
        <div class="page-search__abstract search-abstract">
            <div class="search-abstract__title">Abstract ({{$abstracts->total()}})</div>
            @foreach($abstracts as $item)
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
            @if($abstracts->hasPages())
                <div class="page-search__pagination">
                    @if($abstracts->currentPage() != 1)
                    <button wire:click="navigatePreviousPage()">« Previous</button>
                    @endif
                    @if($abstracts->currentPage() != $abstracts->lastPage())
                    <button wire:click="navigateNextPage()">Next »</button>
                    @endif
                    <p>page {{$abstracts->currentPage()}} of {{$abstracts->lastPage()}}</p>
                </div>
            @endif
        </div>
    @endif
    @elseif($queryContent)
    <p class="page-search__no-result">Aucun résultat</p>
    @endif
</div>
