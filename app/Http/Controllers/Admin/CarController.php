<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
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

        $query = Car::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(CarResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Car List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:225',
            'max_person' => 'required|string|max:225'
        ]);

        $data = [
            'name' => $request->name,
            'max_person' => $request->max_person,
        ];

        $save = Car::create($data);
        return $this->success(new CarResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new CarResource($find), 'Car Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $data = [
            'name' => $request->name ?? $find->name,
            'max_person' => $request->max_person ?? $find->max_person,
        ];


        $find->update($data);

        return $this->success(new CarResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = Car::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
