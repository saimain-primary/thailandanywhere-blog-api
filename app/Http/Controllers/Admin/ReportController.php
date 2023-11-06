<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Http\Resources\AdminResource;
use App\Http\Controllers\Controller;

use App\Traits\HttpResponses;

use DB;

class ReportController extends Controller
{
    use HttpResponses;

    /**
     * Get all bookings sales.
     */
    public function salesAmount(Request $request)
    {
        $data = Booking::select('created_by', DB::raw('SUM(grand_total) as total'))
        ->groupBy('created_by')
        ->get();
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $amount[] = $result->total;
        }

        $data = array(
            'agents' => $agents,
            'amount' => $amount,
        );

        return $this->success($data,'Sales Amount');

    }

    public function salesCount(Request $request)
    {
        $data = Booking::select('created_by', DB::raw('COUNT(grand_total) as total'))
        ->groupBy('created_by')
        ->get();
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $amount[] = $result->total;
        }

        $data = array(
            'agents' => $agents,
            'amount' => $amount,
        );

        return $this->success($data,'Sales Count');

    }

    public function bookingsCount(Request $request)
    {
        $data = Booking::select('created_by', DB::raw('COUNT(id) as total'))
        ->groupBy('created_by')
        ->get();
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $booking[] = $result->total;
        }

        $data = array(
            'agents' => $agents,
            'bookings' => $booking,
        );

        return $this->success($data,'Bookings Count');

    }

    public function reservationsCount(Request $request)
    {

        $data = BookingItem::select('product_type', DB::raw('COUNT(id) as total'))
        ->groupBy('product_type')
        ->get();

        
        foreach($data as $result){
            $reserve_types = substr($result->product_type,11);

            $type[] = $reserve_types;
            $booking[] = $result->total;
        }
 
         $data = array(
             'types' => $type,
             'bookings' => $booking,
         );

         return $this->success($data,'Reservations Count');

 

    }

}
