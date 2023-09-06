<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Room::query();


        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        if ($request->hotel_id) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $data = $query->paginate($limit);
        return $this->success(RoomResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Room List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        $save = Room::create([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
            'extra_price' => $request->extra_price,
            'room_price' => $request->room_price,
            'description' => $request->description,
        ]);

        return $this->success(new RoomResource($save), 'Successfully created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        return $this->success(new RoomResource($room), 'Room Detail', 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        $room->update([
            'name' => $request->name ?? $room->name,
            'hotel_id' => $request->hotel_id ?? $room->hotel_id,
            'description' => $request->description ?? $room->description,
            'extra_price' => $request->extra_price ?? $room->extra_price,
            'room_price' => $request->room_price ?? $room->room_price,
        ]);

        return $this->success(new RoomResource($room), 'Successfully updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        $room->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
