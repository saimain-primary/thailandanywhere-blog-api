<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Models\AirlineTicket;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\AirlineTicketResource;

class AirlineTicketController extends Controller
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

        $query = AirlineTicket::query();

        if ($search) {
            $query->where('description', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(AirlineTicketResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Airline Ticket List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'airline_id' => $request->airline_id,
            'price' => $request->price,
            'description' => $request->description,
        ];

        $save = AirlineTicket::create($data);

        return $this->success(new AirlineTicketResource($save), 'Successfully created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(AirlineTicket $airline_ticket)
    {
        return $this->success(new AirlineTicketResource($airline_ticket), 'Airline Ticket Detail', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AirlineTicket $airline_ticket)
    {
        $airline_ticket->update([
            'price' => $request->price ?? $airline_ticket->price,
            'airline_id' => $request->airline_id ?? $airline_ticket->airline_id,
            'description' => $request->description ?? $airline_ticket->description,
        ]);

        return $this->success(new AirlineTicketResource($airline_ticket), 'Successfully updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AirlineTicket $airline_ticket)
    {
        $airline_ticket->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
