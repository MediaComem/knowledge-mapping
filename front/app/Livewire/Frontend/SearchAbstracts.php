<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Services\BibliographyService;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class SearchAbstracts extends Component
{

    use WithPagination;

    private $abstracts;
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
        if ($this->abstracts) {
            $abstracts = $this->abstracts->paginate(10);
            $abstracts->getCollection()->transform(function ($abstract) {
                $abstractArray = $abstract->toArray();
                $formattedAbstract = $this->bibliographyService->formatSearchResults($abstractArray['abstract'], $this->searchValues);
                $abstractArray['abstract'] = $formattedAbstract;
                return $abstractArray;
            });
        } else {
            $abstracts = new Paginator([], 10);
        }
        return view('livewire.frontend.search-abstracts', [
            'abstracts' => $abstracts,
        ]);
    }

    #[On('search-panel-changed')]
    public function updateSearchParameters($searchParameters): void
    {
        $this->queryContent = $query = $searchParameters['queryContent'];

        if (empty($query)) {
            $this->abstracts = null;
            return;
        }

        $this->queryDateFrom = $searchParameters['queryDateFrom'];
        $this->queryDateTo = $searchParameters['queryDateTo'];
        $this->searchValues = $searchParameters['searchValues'];
        $min = $searchParameters['minDatasetDate'];
        $max = $searchParameters['maxDatasetDate'];

        $this->updateAbstractsPagination();
        $abstracts = $this->abstracts->get();
        $potentialMinDates = array_filter(array($abstracts->min('items_min_year')));
        $potentialMaxDates = array_filter(array($abstracts->max('items_max_year')));
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

    private function updateAbstractsPagination(): void
    {
        $this->abstracts = $this->bibliographyService->getAbstractsBuilder($this->queryDateFrom, $this->queryDateTo, $this->searchValues);
    }

    public function navigateNextPage(): void
    {
        $this->updateAbstractsPagination();
        $this->nextPage();
    }

    public function navigatePreviousPage(): void
    {
        $this->updatePapersPagination();
        $this->previousPage();
    }
}
