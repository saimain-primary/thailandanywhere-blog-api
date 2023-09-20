<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AirlineResource;
use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->query('limit', 10);
        $search = $request->query('search');

        $query = Airline::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($limit);
        return $this->success(AirlineResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Airline List');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $save = Airline::create([
            'name' => $request->name,
            'legal_name' => $request->legal_name,
            'starting_balance' => $request->starting_balance,
        ]);


        if($file = $request->file('contract')) {
            $fileData = $this->uploads($file, 'contracts/');
            $save['contract'] =  $fileData['fileName'];
        }

        return $this->success(new AirlineResource($save), 'Successfully created', 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Airline $airline)
    {
        return $this->success(new AirlineResource($airline), 'Airline Detail', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Airline $airline)
    {
        $airline->update([
            'name' => $request->name ?? $airline->name,
            'legal_name' => $request->legal_name ?? $airline->legal_name,
            'starting_balance' => $request->starting_balance ?? $airline->starting_balance,
        ]);

        if($file = $request->file('contract')) {
            $fileData = $this->uploads($file, 'contracts/');
            $airline->update([
                'contract' => $fileData['fileName']
            ]);
        }

        return $this->success(new AirlineResource($airline), 'Successfully updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airline $airline)
    {
        $airline->delete();
        return $this->success(null, 'Successfully deleted', 200);
    }
}
