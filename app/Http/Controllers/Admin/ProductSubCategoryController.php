<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\ProductSubCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSubCategoryResource;

class ProductSubCategoryController extends Controller
{
    use ImageManager;
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = ProductSubCategory::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(ProductSubCategoryResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Product Sub Category List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'category_id' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id
        ];

        $save = ProductSubCategory::create($data);
        return $this->success(new ProductSubCategoryResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = ProductSubCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new ProductSubCategoryResource($find), 'Product Sub Category Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $find = ProductSubCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'category_id' => $request->category_id ?? $find->category_id,
        ];


        $find->update($data);

        return $this->success(new ProductSubCategoryResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = ProductSubCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
