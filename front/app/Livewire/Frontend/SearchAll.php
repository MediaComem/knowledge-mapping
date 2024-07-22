<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Services\BibliographyService;

class SearchAll extends Component
{

    public $authors;
    public $papers;
    public $abstracts;
    public $queryContent;
    public $queryDateFrom;
    public $queryDateTo;
    public $searchValues;
    public $queryDateMin;
    public $queryDateMax;
    private $bibliographyService;

    public function boot(BibliographyService $bibliographyService): void
    {
        $this->bibliographyService = $bibliographyService;
    }

    public function render(): view
    {
        return view('livewire.frontend.search-all');
    }

    #[On('search-panel-changed')]
    public function updateSearchParameters($searchParameters): void
    {
        $this->queryContent = $query = $searchParameters['queryContent'];
        if (empty($query)) {
            $this->resetQueryResults();
            return;
        }

        $this->queryDateFrom = $queryDateFrom = $searchParameters['queryDateFrom'];
        $this->queryDateTo = $queryDateTo = $searchParameters['queryDateTo'];
        $this->searchValues = $searchValues = $searchParameters['searchValues'];
        $min = $searchParameters['minDatasetDate'];
        $max = $searchParameters['maxDatasetDate'];

        $this->authors = $this->bibliographyService->searchAuthors(
            dateFrom: $queryDateFrom,
            dateTo: $queryDateTo,
            searchValues: $searchValues
        );
        $this->papers = $this->bibliographyService->searchPapers(
            dateFrom: $queryDateFrom,
            dateTo: $queryDateTo,
            searchValues: $searchValues
        );
        $this->abstracts = $this->bibliographyService->searchAbstracts(
            dateFrom: $queryDateFrom,
            dateTo: $queryDateTo,
            searchValues: $searchValues
        );
        foreach ($this->abstracts as $abstract) {
            $abstract["abstract"] = $this->bibliographyService->formatSearchResults($abstract["abstract"], $searchValues);
        }

        $potentialMinDates = array_filter(array($this->authors->min('items_min_year'), $this->papers->min('year'), $this->abstracts->min('year')));
        $potentialMaxDates = array_filter(array($this->authors->max('items_max_year'), $this->papers->max('year'), $this->abstracts->max('year')));
        if (!count($potentialMinDates) && !count($potentialMaxDates)) {
            $this->queryDateMin = $min;
            $this->queryDateMax = $max;
            return;
        }
        $this->queryDateMin = min($potentialMinDates);
        $this->queryDateMax = max($potentialMaxDates);

        $this->dispatch("update-date-range", [
            'queryDateMin' => $this->queryDateMin,
            'queryDateMax' => $this->queryDateMax,
            'queryDateFrom' => $this->queryDateFrom,
            'queryDateTo' => $this->queryDateTo,
        ]);
    }

    private function resetQueryResults(): void
    {
        $this->authors = null;
        $this->papers = null;
        $this->abstracts = null;
    }
}
