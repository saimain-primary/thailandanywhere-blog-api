<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
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

        $query = ProductCategory::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(ProductCategoryResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Product Category List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'icon' => 'sometimes|image|max:2048'  // 2 MB
        ]);

        $data = [
            'name' => $request->name
        ];

        if ($file = $request->file('icon')) {
            $fileData = $this->uploads($file, 'images/');
            $data['icon'] = $fileData['fileName'];
        }

        $save = ProductCategory::create($data);
        return $this->success(new ProductCategoryResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = ProductCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new ProductCategoryResource($find), 'Product Category Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $find = ProductCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name
        ];

        if ($file = $request->file('icon')) {
            // delete old icon
            if ($find->icon) {
                Storage::delete($find->icon);
            }

            $fileData = $this->uploads($file, 'images/');
            $data['icon'] = $fileData['fileName'];
        }

        $find->update($data);

        return $this->success(new ProductCategoryResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = ProductCategory::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
