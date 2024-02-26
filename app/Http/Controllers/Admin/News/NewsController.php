<?php

namespace App\Http\Controllers\Admin\News;

use App\Enums\NewsStatusEnum;
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

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'text' => 'required|string',
            'status' => 'required|string|in:' . implode(',', NewsStatusEnum::values()),
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        $image_path = $request->file('image')->store('image', 'public');

        $news = new News();
        $news->title = $request->input('title');
        $news->description = $request->input('description');
        $news->text= $request->input('text');
        $news->status = $request->input('status');
        $news->created_by = $request->user()->id;
        $news->updated_by = $request->user()->id;
        $news->image_path = '/storage/' . $image_path;
        $news->save();

        return response()->json($news);
    }

    public function update(Request $request, string $newsId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'text' => 'required|string',
            'status' => 'required|string|in:' . implode(',', NewsStatusEnum::values()),
            'image' => 'image|mimes:jpg,png,jpeg,gif|max:2048'
        ]);

        $news = News::query()->findOrFail($newsId);
        $news->title = $request->input('title');
        $news->description = $request->input('description');
        $news->text= $request->input('text');
        $news->status = $request->input('status');
        $news->updated_by = $request->user()->id;

        if ($request->hasFile('image')) {
            $image_path = $request->file('image')->store('images', 'public');
            $news->image_path = '/storage/' . $image_path;

            //TODO: ?delete old image
        }
        $news->save();

        return response()->json($news);
    }


    public function destroy(string $newsId)
    {
        News::query()->findOrFail($newsId)->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
