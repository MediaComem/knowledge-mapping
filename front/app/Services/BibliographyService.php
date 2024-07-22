<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class BibliographyService
{
    public function searchAuthors(
        int $dateFrom = null,
        int $dateTo = null,
        array $searchValues = null
    ): Collection {
        return $this->getAuthorsBuilder(
            dateFrom: $dateFrom,
            dateTo: $dateTo,
            searchValues: $searchValues
        )->get();
    }

    public function getAuthorsBuilder(
        int $dateFrom = null,
        int $dateTo = null,
        array $searchValues
    ): Builder {
        return Author::with('items:year')->select('id', 'surname', 'name')->where(function ($q) use ($searchValues) {
            foreach ($searchValues as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
                $q->orWhere('surname', 'like', "%{$value}%");
            }
        })->whereHas('items', function ($q) use ($dateFrom, $dateTo) {
            $q->whereBetween('year', [$dateFrom, $dateTo]);
        })->withCount('items')
            ->withMin('items', 'year')
            ->withMax('items', 'year');
    }

    public function searchPapers(
        int $dateFrom = null,
        int $dateTo = null,
        int $topicId = null,
        int $topicBase = null,
        array $searchValues = null
    ): Collection {
        return $this->getPapersBuilder(
            dateFrom: $dateFrom,
            dateTo: $dateTo,
            topicId: $topicId,
            topicBase: $topicBase,
            searchValues: $searchValues
        )->get();
    }

    public function getPapersBuilder(
        int $dateFrom = null,
        int $dateTo = null,
        int $topicId = null,
        int $topicBase = null,
        array $searchValues = null
    ): Builder {
        if ($topicId && $topicId != -1 && $topicBase) {
            $topicId--; // 0-based index
            $topicBaseId = 'base_' . $topicBase . '_' . $topicId;
        }

        $items = Item::with("authors");
        if (isset($topicBaseId)) {
            $items->join('topic_docs', 'items.id', '=', 'topic_docs.item_id')
                ->where('topic_id', '=', $topicBaseId)
                ->distinct();
        }
        $items->with('authors')
            ->select('id', 'title', 'date')->where('has_abstract', '=', '1')
            ->when($dateFrom, function ($q, $dateFrom) {
                $q->where('year', '>=', $dateFrom);
            })
            ->when($dateTo, function ($q, $dateTo) {
                $q->where('year', '<=', $dateTo);
            });
        if ($searchValues != null) {
            $items->when($searchValues, function ($q, $searchValues) {
                $q->where(function ($q) use ($searchValues) {
                    foreach ($searchValues as $value) {
                        $q->orWhere('title', 'like', "%{$value}%");
                    }
                });
            });
        }
        $items->distinct();
        //dd($items->get());
        return $items;
    }

    public function searchAbstracts(
        int $dateFrom = null,
        int $dateTo = null,
        array $searchValues = null
    ): Collection {
        return $this->getAbstractsBuilder($dateFrom, $dateTo, $searchValues)->get();
    }

    public function getAbstractsBuilder(
        int $dateFrom = null,
        int $dateTo = null,
        array $searchValues = null
    ): Builder {
        //dd($dateFrom, $dateTo, $searchValues);
        return Item::select('id', 'title', 'year', 'abstract')->where('has_abstract', '=', '1')
            ->whereBetween('year', [$dateFrom, $dateTo])
            ->where(function ($q) use ($searchValues) {
                foreach ($searchValues as $value) {
                    $q->orWhere('abstract', 'like', "%{$value}%");
                }
            })->distinct();
    }

    public function formatSearchResults($textToFormat, $searchValues): string
    {
        $shownParam = "";
        foreach ($searchValues as $searchValue) {
            if (stripos($textToFormat, $searchValue) !== false) {
                $shownParam = $searchValue;
                break;
            }
        }
        if (empty($shownParam)) {
            return $textToFormat;
        }

        return "[...]" . Str::limit(substr($textToFormat, max(stripos($textToFormat, $shownParam) - 40, 0)), $limit = 150, $end = '') . "[...]";
    }
}
