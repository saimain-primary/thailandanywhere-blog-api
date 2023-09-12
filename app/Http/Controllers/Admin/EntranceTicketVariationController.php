<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntranceTicketVariationResource;
use App\Models\EntranceTicketVariation;

class EntranceTicketVariationController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = EntranceTicketVariation::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(EntranceTicketVariationResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Variation List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'age_group' => 'required',
            'price' => 'required',
        ]);

        $save = EntranceTicketVariation::create([
            'name' => $request->name,
            'age_group' => $request->age_group,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return $this->success(new EntranceTicketVariationResource($save), 'Successfully created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(EntranceTicketVariation $entrance_tickets_variation)
    {
        return $this->success(new EntranceTicketVariationResource($entrance_tickets_variation), 'Variation Detail', 200);

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EntranceTicketVariation $entrance_tickets_variation)
    {
        $entrance_tickets_variation->update([
            'name' => $request->name ?? $entrance_tickets_variation->name,
            'age_group' => $request->age_group ?? $entrance_tickets_variation->age_group,
            'price' => $request->price ?? $entrance_tickets_variation->price,
            'description' => $request->description ?? $entrance_tickets_variation->description,
        ]);

        return $this->success(new EntranceTicketVariationResource($entrance_tickets_variation), 'Successfully updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EntranceTicketVariation $entrance_tickets_variation)
    {
        $entrance_tickets_variation->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
