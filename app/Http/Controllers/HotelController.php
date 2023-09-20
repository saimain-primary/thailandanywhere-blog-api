<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Models\HotelContract;
use App\Traits\HttpResponses;
use App\Http\Resources\HotelResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;

class HotelController extends Controller
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
        dd($request->file('contracts'));
        $save = Hotel::create([
             'name' => $request->name,
             'payment_method' => $request->payment_method,
             'bank_name' => $request->bank_name,
             'bank_account_number' => $request->bank_account_number,
             'city_id' => $request->city_id,
             'place' => $request->place,
             'legal_name' => $request->legal_name,
             'contract_due' => $request->contract_due,
         ]);

        $contractArr = [];

        if($request->file('contracts')) {
            foreach($request->file('contracts') as $file) {
                $fileName = $file->getClientOriginalName();
                $filePath = 'contracts/' . $fileName;
                dd($file);
                dd(file_get_contents($file));
                $path = Storage::disk('public')->put($filePath, file_get_contents($file));
                // $path = Storage::disk('public')->url($path);
                // $fileData = $this->uploads($file, 'contracts/');
                // $contractArr[] = [
                //     'hotel_id' => $save->id,
                //     'file' => $fileData['fileName']
                // ];
            }

            HotelContract::insert($contractArr);
        }

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
            'bank_name' => $request->bank_name ?? $hotel->bank_name,
            'payment_method' => $request->payment_method ?? $hotel->payment_method,
            'bank_account_number' => $request->bank_account_number ?? $hotel->bank_account_number,
            'legal_name' => $request->legal_name,
            'contract_due' => $request->contract_due,
        ]);

        $contractArr = [];

        if($request->file('contracts')) {
            foreach($request->file('contracts') as $file) {
                $fileData = $this->uploads($file, '/contracts/');
                $contractArr[] = [
                    'hotel_id' => $hotel->id,
                    'file' => $fileData['fileName']
                ];
            }

            HotelContract::insert($contractArr);
        }

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
