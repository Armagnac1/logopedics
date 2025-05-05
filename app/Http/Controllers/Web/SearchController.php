<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CrossDomain\Search\SearchServiceInterface;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $searchService;

    public function __construct(SearchServiceInterface $searchService)
    {
        $this->searchService = $searchService;
    }

    public function index(Request $request)
    {
        $searchInput = $request->input('search');
        $result = $this->searchService->getForIndex($searchInput);

        return response()->json(['result' => $result]);
    }
}
