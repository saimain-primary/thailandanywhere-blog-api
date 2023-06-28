<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    use HttpResponses;
    use ImageManager;


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);


        if($file = $request->file('image')) {
            $fileData = $this->uploads($file, 'images/');
            $category->image = $fileData['fileName'];
        }

        $category->save();

        return $this->success(new CategoryResource($category), 'Successfully created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new CategoryResource($category), 'Category Detail', 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->error(null, 'Data not found', 404);
        }

        $category->name = $request->name ?? $category->name;
        $category->slug = Str::slug($request->name) ?? $category->slug;

        if($file = $request->file('image')) {
            $fileData = $this->uploads($file, 'images/');
            $category->image = $fileData['fileName'];
        }

        $category->update();

        return $this->success(new CategoryResource($category), 'Successfully updated', 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if(!$category) {
            return $this->error(null, 'Data not found', 404);
        }

        // TODO :: check blog using this category

        $category->delete();
        return $this->success(null, 'Successfully deleted', 200);

    }
}
