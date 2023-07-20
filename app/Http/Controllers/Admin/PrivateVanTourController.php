<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\PrivateVanTour;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Resources\PrivateVanTourResource;

class PrivateVanTourController extends Controller
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

        $query = PrivateVanTour::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(PrivateVanTourResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Private Van Tour List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'description' => 'required|string|max:225',
            'cover_image' => 'required|image|max:2048',
            'city_ids' => 'required',
            'car_ids' => 'required',
            'tag_ids' => 'required',
            'destination_ids' => 'required',
            'sku_code' => 'required|' . Rule::unique('private_van_tours'),
        ]);


        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'sku_code' => $request->sku_code,
            'long_description' => $request->long_description,
        ];

        if ($file = $request->file('cover_image')) {
            $fileData = $this->uploads($file, 'images/');
            $data['cover_image'] = $fileData['fileName'];
        }

        $save = PrivateVanTour::create($data);

        if ($request->tag_ids) {
            $save->tags()->sync($request->tag_ids);
        }


        return $this->success(new PrivateVanTourResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = PrivateVanTour::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new PrivateVanTourResource($find), 'Product Category Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $find = PrivateVanTour::find($id);
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

        return $this->success(new PrivateVanTourResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = PrivateVanTour::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
