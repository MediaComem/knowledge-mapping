<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Services\BibliographyService;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class SearchAuthors extends Component
{

    use WithPagination;

    private $authors;
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
        $authors = $this->authors ? $this->authors->paginate(10) : new Paginator([], 10);
        return view('livewire.frontend.search-authors', [
            'authors' => $authors,
        ]);
    }

    #[On('search-panel-changed')]
    public function updateSearchParameters($searchParameters): void
    {
        $this->queryContent = $query = $searchParameters['queryContent'];

        if (empty($query)) {
            $this->authors = null;
            return;
        }

        $this->queryDateFrom = $searchParameters['queryDateFrom'];
        $this->queryDateTo = $searchParameters['queryDateTo'];
        $this->searchValues = $searchParameters['searchValues'];
        $min = $searchParameters['minDatasetDate'];
        $max = $searchParameters['maxDatasetDate'];

        $this->updateAuthorsPagination();
        $authors = $this->authors->get();
        $potentialMinDates = array_filter(array($authors->min('items_min_year')));
        $potentialMaxDates = array_filter(array($authors->max('items_max_year')));
        if (!count($potentialMinDates) && !count($potentialMaxDates)) {
            $this->queryDateMin = $min;
            $this->queryDateMax = $max;
            return;
        }
        $this->queryDateMin = min($potentialMinDates);
        $this->queryDateMax = max($potentialMaxDates);

        $this->resetPage();

        $this->dispatch("update-date-range", [
            'queryDateMin' => $this->queryDateMin,
            'queryDateMax' => $this->queryDateMax,
            'queryDateFrom' => $this->queryDateFrom,
            'queryDateTo' => $this->queryDateTo,
        ]);
    }

    private function updateAuthorsPagination(): void
    {
        $this->authors = $this->bibliographyService->getAuthorsBuilder($this->queryDateFrom, $this->queryDateTo, $this->searchValues);
    }

    public function navigateNextPage(): void
    {
        $this->updateAuthorsPagination();
        $this->nextPage();
    }

    public function navigatePreviousPage(): void
    {
        $this->updateAuthorsPagination();
        $this->previousPage();
    }
}
