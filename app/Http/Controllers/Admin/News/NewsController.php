<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\NewsCreateRequest;
use App\Http\Requests\Admin\News\NewsUpdateRequest;
use App\Models\News;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    public function index(): JsonResponse
    {
        $query = News::query();

        //Add filters/sorting etc.

        return response()->json($query->paginate());
    }

    public function show(string $newsId): JsonResponse
    {
        $news = News::query()->findOrFail($newsId);
        return response()->json($news);
    }

    public function create(NewsCreateRequest $request): JsonResponse
    {
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

    public function update(NewsUpdateRequest $request, string $newsId): JsonResponse
    {
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

    public function destroy(string $newsId): JsonResponse
    {
        News::query()->findOrFail($newsId)->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
