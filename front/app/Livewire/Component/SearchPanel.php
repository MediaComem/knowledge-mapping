<?php

namespace App\Livewire\Component;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;

class SearchPanel extends Component
{
    #[Url(as: 'q')]
    public $queryContent;
    #[Url(as: 'dateFrom')]
    public $queryDateFrom;
    #[Url(as: 'dateTo')]
    public $queryDateTo;
    #[Url(as: 'topicId')]
    public $queryTopicId;
    #[Url(as: 'base')]
    public $queryTopicBase;
    public $queryDateMin;
    public $queryDateMax;
    public $minDatasetDate;
    public $maxDatasetDate;

    public function render()
    {
        return view('livewire.component.search-panel');
    }

    public function mount()
    {
        //Get all dates from current dataset
        $data = DB::connection('sqlite2')->table('items')
            ->select('date')
            ->where([
                ['date', '<>', '']
            ])
            ->get();

        $years = array();
        foreach ($data as $item) {
            // Check for 4 digit number (year)
            if (preg_match('/\b\d{4}\b/', $item->date, $matches)) {
                array_push($years, intval($matches[0]));
            }
        }
        $this->maxDatasetDate = max($years);
        $this->minDatasetDate = min($years);

        $this->queryDateFrom = $this->queryDateFrom ?? $this->minDatasetDate;
        $this->queryDateTo = $this->queryDateTo ?? $this->maxDatasetDate;

        //Trigger search on load only if a query or a topic is set in the URL
        if (isset($this->queryContent) || (isset($this->queryTopicId) && isset($this->queryTopicBase))) {
            $this->dispatch("form-submitted", dateFrom: $this->queryDateFrom, dateTo: $this->queryDateTo);
        }
    }

    #[On('update-date-range')]
    public function udpateDateRange($dateRange)
    {
        $this->queryDateMin = $dateRange['queryDateMin'];
        $this->queryDateMax = $dateRange['queryDateMax'];
        $this->queryDateFrom = $dateRange['queryDateFrom'];
        $this->queryDateTo = $dateRange['queryDateTo'];
    }

    #[On('form-submitted')]
    public function search($dateFrom = null, $dateTo = null)
    {
        // If the date range is not set, use the min and max dataset dates
        // --> Happens only on first load
        if (isset($dateFrom) && isset($dateTo)) {
            $this->queryDateFrom = $dateFrom;
            $this->queryDateTo = $dateTo;
        } else {
            $this->queryDateFrom = $this->minDatasetDate;
            $this->queryDateTo = $this->maxDatasetDate;
        }

        $validated = $this->validate([
            'queryContent' => 'nullable|string|max:255',
            'queryTopicId' => 'nullable|integer',
            'queryTopicBase' => 'nullable|integer',
            'queryDateFrom' => 'digits:4|integer',
            'queryDateTo' => 'digits:4|integer',
        ]);
        // Remove double quotes from query
        $terms = str_replace('"', '', $this->queryContent);

        // Split on whitespaces & ignore empty (eg. trailing space)
        $searchValues = $terms != "" ? preg_split('/\s+/', $terms, -1, PREG_SPLIT_NO_EMPTY) : null;

        $this->dispatch('search-panel-changed', [
            'queryContent' => $this->queryContent,
            // Avoid invalid date range (out of dataset range)
            'queryDateFrom' => max(intVal($validated["queryDateFrom"]), $this->minDatasetDate),
            'queryDateTo' => min(intVal($validated["queryDateTo"]), $this->maxDatasetDate),
            'queryTopicId' => $this->queryTopicId,
            'queryTopicBase' => $this->queryTopicBase,
            'searchValues' => $searchValues,
            'minDatasetDate' => $this->minDatasetDate,
            'maxDatasetDate' => $this->maxDatasetDate
        ]);
    }
}
