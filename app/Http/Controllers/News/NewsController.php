<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index() 
    {
        $query = News::query();

        //Add filters/sorting etc.

        return response()->json($query->paginate());
    }

    public function show(string $newsId)
    {
        $news = News::query()->findOrFail($newsId);
        return response()->json($news);
    }

}
