<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    use HttpResponses;

    public function getList(Request $request)
    {
        $limit = $request->query('limit', 10);

        $query = Category::query();

        $data = $query->paginate($limit);
        return $this->success(CategoryResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Category List');
    }
}
