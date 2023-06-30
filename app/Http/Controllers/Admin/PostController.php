<?php

namespace App\Http\Controllers\Admin;

use HTMLPurifier;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Post::query();

        if($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(PostResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Post List');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // $purifier = new HTMLPurifier();
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title . '_' . now()->format('Y-m-d H:i:s') . '_' . rand(0000, 9999));
        $post->content = $request->content;
        $post->save();

        return $this->success($post, 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        if(!$post) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new PostResource($post), 'Post Detail');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, string $slug)
    {
        $post = Post::where('slug', $slug)->first();
        if(!$post) {
            return $this->error(null, 'Data not found', 404);
        }
        $purifier = new HTMLPurifier();


        $post->title = $request->title ?? $post->title;
        $post->slug = $request->title ? Str::slug($request->title . '_' . now()->format('Y-m-d H:i:s') . '_' . rand(0000, 9999)) : $post->slug;
        $post->content = $request->content ?? $purifier->purify($request->content);
        $post->update();

        return $this->success(new PostResource($post), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if(!$post) {
            return $this->error(null, 'Data not found', 404);
        }

        // TODO :: check blog using this post

        $post->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
