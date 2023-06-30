<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    use HttpResponses;

    public function getPost(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Post::query();


        if($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }


        if($request->query('category')) {
            $c = Category::where('slug', $request->query('category'))->first();
            $query->where('category_id', $c->id);
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
}
