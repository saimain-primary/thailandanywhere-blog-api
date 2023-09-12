<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ImageManager;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\EntranceTicket;
use App\Models\EntranceTicketImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\EntranceTicketResource;
use App\Http\Requests\StoreEntranceTicketRequest;
use App\Http\Requests\UpdateEntranceTicketRequest;
use App\Models\EntranceTicketVariation;

class EntranceTicketController extends Controller
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

        $query = EntranceTicket::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $query->orderBy('created_at', 'desc');

        $data = $query->paginate($limit);
        return $this->success(EntranceTicketResource::collection($data)
            ->additional([
                'meta' => [
                    'total_page' => (int) ceil($data->total() / $data->perPage()),
                ],
            ])
            ->response()
            ->getData(), 'Entrance Ticket List');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntranceTicketRequest $request)
    {

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'provider' => $request->provider,
            'cancellation_policy_id' => $request->cancellation_policy_id,
        ];

        if ($file = $request->file('cover_image')) {
            $fileData = $this->uploads($file, 'images/');
            $data['cover_image'] = $fileData['fileName'];
        }

        $save = EntranceTicket::create($data);

        if ($request->tag_ids) {
            $save->tags()->sync($request->tag_ids);
        }

        if ($request->city_ids) {
            $save->cities()->sync($request->city_ids);
        }

        if ($request->category_ids) {
            $save->categories()->sync($request->category_ids);
        }

        if ($request->variations) {
            $save->variations()->sync($request->variations);
        }

        if($request->file('images')) {
            foreach ($request->file('images') as $image) {
                $fileData = $this->uploads($image, 'images/');
                EntranceTicketImage::create(['entrance_ticket_id' => $save->id, 'image' => $fileData['fileName']]);
            };
        }

        // foreach ($request->variations as $variation) {
        //     EntranceTicketVariation::create(['entrance_ticket_id' => $save->id, 'name' => $variation['name'], 'age_group' => $variation['age_group'], 'price' => $variation['price']]);
        // };

        return $this->success(new EntranceTicketResource($save), 'Successfully created');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $find = EntranceTicket::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        return $this->success(new EntranceTicketResource($find), 'Entrance Ticket Detail');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntranceTicketRequest $request, string $id)
    {
        $find = EntranceTicket::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }


        $data = [
            'name' => $request->name ?? $find->name,
            'description' => $request->description  ?? $find->description,
            'provider' => $request->provider ?? $find->provider,
            'cancellation_policy_id' => $request->cancellation_policy_id ?? $find->cancellation_policy_id,
        ];


        if ($file = $request->file('cover_image')) {
            $fileData = $this->uploads($file, 'images/');
            $data['cover_image'] = $fileData['fileName'];

            if ($find->cover_image) {
                Storage::delete('public/images/' . $find->cover_image);
            }
        }

        $find->update($data);


        if ($request->tag_ids) {
            $find->tags()->sync($request->tag_ids);
        }

        if ($request->city_ids) {
            $find->cities()->sync($request->city_ids);
        }

        if ($request->category_ids) {
            $find->categories()->sync($request->category_ids);
        }

        if ($request->variations) {
            $find->variations()->sync($request->variations);
        }



        if ($request->file('images')) {
            foreach ($request->file('images') as $image) {
                // Delete existing images
                if (count($find->images) > 0) {
                    foreach ($find->images as $exImage) {
                        // Delete the file from storage
                        Storage::delete('public/images/' . $exImage->image);
                        // Delete the image from the database
                        $exImage->delete();
                    }
                }

                $fileData = $this->uploads($image, 'images/');
                EntranceTicketImage::create(['entrance_ticket_id' => $find->id, 'image' => $fileData['fileName']]);
            };
        }

        // if ($request->variations) {
        //     foreach ($request->variations as $variation) {
        //         if (count($find->variations) > 0) {
        //             foreach ($find->variations as $v) {
        //                 $v->delete();
        //             }
        //         }
        //         EntranceTicketVariation::create(['entrance_ticket_id' => $find->id, 'name' => $variation['name'], 'age_group' => $variation['age_group'], 'price' => $variation['price']]);
        //     };
        // }


        return $this->success(new EntranceTicketResource($find), 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $find = EntranceTicket::find($id);
        if (!$find) {
            return $this->error(null, 'Data not found', 404);
        }

        $find->tags()->detach();
        $find->categories()->detach();
        $find->cities()->detach();

        Storage::delete('public/images/' . $find->cover_image);

        foreach ($find->images as $image) {
            // Delete the file from storage
            Storage::delete('public/images/' . $image->image);
            // Delete the image from the database
            $image->delete();
        }

        foreach ($find->variations as $variation) {
            $variation->delete();
        }

        $find->delete();
        return $this->success(null, 'Successfully deleted');
    }
}
