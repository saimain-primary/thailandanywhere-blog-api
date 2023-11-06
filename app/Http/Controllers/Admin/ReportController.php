<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Booking;
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

}
