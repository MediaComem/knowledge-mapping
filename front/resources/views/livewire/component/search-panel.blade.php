    <div>

    <form id="page-search__form" class="page-search__form" wire:submit="search">
      <div class="page-search__wrap">
        <input wire:model="queryContent" type="search" name="queryContent" value="{{$queryContent}}" class="page-search__input clear" placeholder="Author, Paper, Sailent term...">
        <span id="js-clear" class="page-search__clear"><img src="/img/clear-icon.svg" alt=""></span>
        <button type="submit" class="page-search__submit"><img src="/img/search-blue.svg" alt="Search"></button>
      </div>
        <div class="page-search__filter">
         <div class="page-search__datewrap">
          <input wire:model="queryDateFrom" type="number" id="inputDate1" value="{{$queryDateFrom}}" name="dateFrom" class="page-search__date">
          <div wire:ignore id="slider-date" class="page-search__range" data-max="{{$maxDatasetDate}}" data-min="{{$minDatasetDate}}"></div>
          <input wire:model="queryDateTo" type="number" id="inputDate2" value="{{$queryDateTo}}" name="dateTo" class="page-search__date">
            </div>
        </div>
        <input type="range" class="page-search__years">
        @if ($errors->any())
            <p class="page-search__error">The search query must be a string and dates must be integers. Please try again.</p>
        @endif
    </form>
</div>
