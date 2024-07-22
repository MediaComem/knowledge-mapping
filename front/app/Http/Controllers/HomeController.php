<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Author;
use App\Models\ClusterName;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\BibliographyService;

class HomeController extends Controller
{
    private $bibliographyService;

    public function __construct(BibliographyService $bibliographyService)
    {
        $this->bibliographyService = $bibliographyService;
    }
    /**
     * Load and feed the home page categories and their items
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //Get all items categories
        $cats = DB::connection('sqlite2')->table('items')
            ->select('itemType', DB::connection('sqlite2')->raw('count(*) as total'))
            ->where([
                ['has_abstract', '=', '1']
            ])
            ->groupBy('itemType')
            ->orderBy('total', 'DESC')
            ->get();

        $items = Item::where('has_abstract', '1')->with(['authors'])->orderBy('itemType', 'DESC')->get();

        return view('home')->with([
            'categories' => $cats,
            'data' => $items
        ]);
    }

    /**
     * Load and feed a single item to the view
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $item = Item::find($id);
        $authors = $item->authors;
        return view('view')->with([
            'data' => $item,
            'authors' => $authors,
        ]);
    }

    /**
     * Load and feed the papers page with cluster names
     * @return \Illuminate\View\View
     */
    public function papers()
    {
        $clusters = ClusterName::where('topic_id', 'like', 'base_10_%')->select('name')->orderBy('topic_id')->get();
        $clustersBase20 = ClusterName::where('topic_id', 'like', 'base_20_%')->select('name')->orderBy('topic_id')->get();

        $clusterNames = [];
        foreach ($clusters as $c) {
            $clusterNames[] = $c->name;
        }

        $clusterNames20 = [];
        foreach ($clustersBase20 as $c2) {
            $clusterNames20[] = $c2->name;
        }
        return view('papers')->with([
            'clusterNames' => $clusterNames,
            'clusterNames20' => $clusterNames20
        ]);
    }

    /**
     * Context: API
     * Called from the papers page to
     *   fetch random papers if no cluster is selected
     *   fetch papers by topic and search terms if provided
     * @param int $base Base number (10 or 20)
     * @param string $topic topic id
     * @param string $term Search term(s)
     * @return \Illuminate\View\View
     */
    public function papersByTopic($base = 10, $topic = -1, $term = null)
    {
        // Remove double quotes from query
        $terms = str_replace('"', '', $term);

        // Split on whitespaces & ignore empty (eg. trailing space)
        $searchValues = $terms != "" ? preg_split('/\s+/', $terms, -1, PREG_SPLIT_NO_EMPTY) : null;
        $items = $this->bibliographyService->getPapersBuilder(topicId: $topic, topicBase: $base, searchValues: $searchValues)->paginate(10);

        return view('partials.relatedItems')->with([
            'base' => $base,
            'topic' => $topic,
            'term' => $term,
            'items' => $items,
        ]);
    }


    public function authors()
    {
        $authorsItemsCount = Author::withCount('items')->orderBy('items_count', 'DESC')->get();
        return view('authors-dots')->with([
            'authors_itemsCount' => $authorsItemsCount,
        ]);
    }

    public function author($id)
    {
        $author = Author::find($id);
        return view('author')->with([
            'author' => $author
        ]);
    }



    public function search(Request $request)
    {
        return view('search');
    }

    public function searchAbstract(Request $request)
    {
        return view('searchabstract');
    }
    public function searchPapers(Request $request)
    {
        return view('searchpapers');
    }

    public function searchAuthors(Request $request)
    {
        return view('searchauthors');
    }

    public function authorsPapers($name = null)
    {

        if ($name != null) {

            $n = explode("_", $name);
            $author = Author::where('name', 'like', '%' . $n[0] . '%')
                ->where('surname', 'like', '%' . $n[1] . '%')
                ->first();

            $count = 10;
            $count = Item::select('items.*')
                ->join('author_item', 'items.id', '=', 'author_item.item_id')
                ->where('author_id', '=', $author->id)
                ->count();


            $items = Item::select('items.*')
                ->join('author_item', 'items.id', '=', 'author_item.item_id')
                ->where('author_id', '=', $author->id)
                ->orderBy('date', 'DESC')
                ->paginate(10);

            // // $items->setPath('/api/authors');
            return view('partials.authorsPapers')->with([
                'count' => $count,
                'author' => $author,
                'items' => $items
            ]);
        } else {
            $topAuthors = Author::withCount('items')->withMin('items', 'year')->withMax('items', 'year')->orderBy('items_count', 'DESC')->take(10)->get();
            return view('partials.topTenAuthors')->with([
                'topAuthors' => $topAuthors,
            ]);
        }
    }

    public function introduction()
    {
        return view('introduction')->with([]);
    }


    public function method()
    {
        return view('method')->with([]);
    }


    public function credits()
    {
        return view('credits')->with([]);
    }
}
