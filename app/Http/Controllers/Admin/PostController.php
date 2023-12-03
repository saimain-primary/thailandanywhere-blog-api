<?php

namespace App\Http\Controllers\Admin;

use HTMLPurifier;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    use HttpResponses;
    use ImageManager;
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
        $post = new Post();
        $post->title = $request->title;
        $post->slug = Str::slug($request->title . '_' . now()->format('Y-m-d H:i:s') . '_' . rand(0000, 9999));
        $post->content = $request->content;
        $post->category_id = $request->category_id;

        $tags = explode(',', $request->tags);

        foreach ($tags as $tag) {
            if(!Tag::where('name', $tag)->first()) {
                Tag::create(['name' => $tag , 'slug' => Str::slug($tag)]);
            }
        }

        $post->tags  = json_encode($tags);


        if($file = $request->file('feature_image')) {
            $fileData = $this->uploads($file, 'images/');
            $post->featured_image = $fileData['fileName'];
        }

        $imagesArr = [];

        if($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $fileData = $this->uploads($image, 'images/');
                $imagesArr[] = $fileData['fileName'];
            }
        }

        $post->published_at = now();
        $post->images = json_encode($imagesArr);
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


        $post->title = $request->title ?? $post->title;
        $post->slug = $request->title ? Str::slug($request->title . '_' . now()->format('Y-m-d H:i:s') . '_' . rand(0000, 9999)) : $post->slug;
        $post->content = $request->content ?? $post->content;
        $post->category_id = $request->category_id ?? $post->content;
        $post->tags  =  $request->tags ? json_encode(explode(',', $request->tags)) : $post->tags;
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
