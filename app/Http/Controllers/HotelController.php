<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;

class HotelController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Hotel::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(HotelResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Hotel List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHotelRequest $request)
    {
        $save = Hotel::create([
             'name' => $request->name,
             'city_id' => $request->city_id,
             'place' => $request->place,
         ]);

        return $this->success(new HotelResource($save), 'Successfully created', 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel)
    {
        return $this->success(new HotelResource($hotel), 'Hotel Detail', 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $hotel->update([
            'name' => $request->name ?? $hotel->name,
            'city_id' => $request->city_id ?? $hotel->city_id,
            'place' => $request->place ?? $hotel->place,
        ]);

        return $this->success(new HotelResource($hotel), 'Successfully updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
