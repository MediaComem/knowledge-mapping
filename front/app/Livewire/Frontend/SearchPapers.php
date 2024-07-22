<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Contracts\View\View;
use App\Services\BibliographyService;
use Illuminate\Pagination\Paginator;
use Livewire\WithPagination;

class SearchPapers extends Component
{

    use WithPagination;

    private $papers;
    public $queryContent;
    public $queryDateFrom;
    public $queryDateTo;
    public $queryTopicId;
    public $queryTopicBase;
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
        $papers = $this->papers ? $this->papers->paginate(10) : new Paginator([], 10);
        return view('livewire.frontend.search-papers', [
            'papers' => $papers,
        ]);
    }

    #[On('search-panel-changed')]
    public function updateSearchParameters($searchParameters): void
    {
        $this->queryContent = $query = $searchParameters['queryContent'];
        $this->queryTopicId = $topicId = $searchParameters['queryTopicId'];
        $this->queryTopicBase = $topicBase = $searchParameters['queryTopicBase'];

        if (!($query || $topicId && $topicBase)) {
            $this->papers = null;
            return;
        }

        $this->queryDateFrom = $searchParameters['queryDateFrom'];
        $this->queryDateTo = $searchParameters['queryDateTo'];
        $this->searchValues = $searchParameters['searchValues'];
        $min = $searchParameters['minDatasetDate'];
        $max = $searchParameters['maxDatasetDate'];

        $this->updatePapersPagination();
        $papers = $this->papers->get();
        $potentialMinDates = array_filter(array($papers->min('items_min_year')));
        $potentialMaxDates = array_filter(array($papers->max('items_max_year')));
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

    private function updatePapersPagination(): void
    {
        $this->papers = $this->bibliographyService->getPapersBuilder(
            dateFrom: $this->queryDateFrom,
            dateTo: $this->queryDateTo,
            topicId: $this->queryTopicId,
            topicBase: $this->queryTopicBase,
            searchValues: $this->searchValues
        );
    }

    public function navigateNextPage(): void
    {
        $this->updatePapersPagination();
        $this->nextPage();
    }

    public function navigatePreviousPage(): void
    {
        $this->updatePapersPagination();
        $this->previousPage();
    }
}
