<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Auth;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;

class NewsController extends Controller
{
    /**
     * Constructor method to enable use the same routes as authenticated or guest.
     */
    public function __construct()
    {
        if(request()->headers->has('Authorization') && !empty(trim(request()->header('Authorization')))){
            $this->middleware('auth:sanctum');
        }
    }

    /**
     * Get a paginated list of news articles with optional search and filters.
     *
     * @param Request $request The HTTP request
     *
     * @return json response
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $newsQuery = News::query();

        /* Apply search */
        if($request->has('search') && !empty($request['search'])){
            $newsQuery->where(function ($query) use($request){
                $query->where('title', 'LIKE', '%' . $request['search'] . '%');
                $query->orWhere('body', 'LIKE', '%' . $request['search'] . '%');
            });
        }
        /***************/

        /* Apply filters */
        if($request->has('source_id') && !empty($request['source_id'])){
            $sources = explode(',', $request['source_id']);
            $newsQuery->whereIn('source_id', $sources);
            if($authUser)
                $this->saveUserPreferredSources($authUser, $sources);
        } else if ($authUser) {
            $this->saveUserPreferredSources($authUser, []);
        }

        if($request->has('category') && !empty($request['category'])){
            $categories = explode(',', $request['category']);
            $newsQuery->whereIn('category', $categories);
            if($authUser)
                $this->saveUserPreferredCategories($authUser, $categories);
        } else if ($authUser) {
            $this->saveUserPreferredCategories($authUser, []);
        }

        if($request->has('author') && !empty($request['author'])){
            $authors = explode(',', $request['author']);
            $newsQuery->whereIn('author', $authors);
            if($authUser)
                $this->saveUserPreferredAuthors($authUser, $authors);
        } else if ($authUser) {
            $this->saveUserPreferredAuthors($authUser, []);
        }

        if($request->has('date') && !empty($request['date'])){
            $newsQuery->whereDate('published_at', $request['date']);
        }
        /* End filters */

        $news = $newsQuery->paginate(10);
        return ResponseHelper::sendResponse($news, 200);
    }

    /**
     * Get a list of available filters and the user's preferred filters.
     *
     * @return json response
     */
    public function getFilters()
    {
        $filters = [
            'categories'                => [],
            'authors'                   => [],
            'preferred_sources'         => '',
            'preferred_categories'      => '',
            'preferred_authors'         => '',
        ];
        $newsQuery = News::query();
        $filters['categories'] = (clone $newsQuery)->whereNotNull('category')
                                                ->where('category', '!=', '')
                                                ->select('category')
                                                ->distinct('category')
                                                ->pluck('category')
                                                ->toArray();
        $filters['authors'] = (clone $newsQuery)->whereNotNull('author')
                                                ->where('author', '!=', '')
                                                ->select('author')
                                                ->distinct('author')
                                                ->pluck('author')
                                                ->toArray();
        $authUser = Auth::user();
        if($authUser){
            $filters['preferred_sources'] = $authUser->preferred_sources;
            $filters['preferred_categories'] = $authUser->preferred_categories;
            $filters['preferred_authors'] = $authUser->preferred_authors;
        }
        return ResponseHelper::sendResponse($filters, 200);
    }

    public function saveUserPreferredSources($authUser, $preferredSources)
    {
        $authUser->update(['preferred_sources' => implode(',', $preferredSources)]);
    }

    /**
     * Save the user's preferred news categories.
     *
     * @param User $authUser The authenticated user
     * @param array $preferredCategories The user's preferred news categories
     *
     * @return void
     */
    public function saveUserPreferredCategories($authUser, $preferredCategories)
    {
        $authUser->update(['preferred_categories' => implode(',', $preferredCategories)]);
    }

    /**
     * Save the user's preferred news authors.
     *
     * @param User $authUser The authenticated user
     * @param array $preferredAuthors The user's preferred news authors
     *
     * @return void
     */
    public function saveUserPreferredAuthors($authUser, $preferredAuthors)
    {
        $authUser->update(['preferred_authors' => implode(',', $preferredAuthors)]);
    }

}
