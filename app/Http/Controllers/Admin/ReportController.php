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
use Carbon\Carbon;

class ReportController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        $from = $request->start_date;
        $to = $request->end_date;

        if($from != '' && $to != '')
        {
            
            $sales_amount = $this->salesAmount($from,$to);
            $sales_count = $this->salesCount($from,$to);
            $bookings = $this->bookingsCount($from,$to);
            $reservations = $this->reservationsCount($from,$to);

            $date = 'From: '.Carbon::parse($from)->format('d F Y').' To: '.Carbon::parse($to)->format('d F Y');
    
        }else{

            $sales_amount = $this->salesAmount();
            $sales_count = $this->salesCount();
            $bookings = $this->bookingsCount();
            $reservations = $this->reservationsCount();

            $date = Carbon::now()->format('d F Y');

        }

        
        $data = array(
            'sales' => $sales_amount,
            'sales-count' => $sales_count,
            'bookings' => $bookings,
            'reservations' => $reservations
        );

        return $this->success($data,$date);

    }

    public function getSelectData($date)
    {

        $sales_amount = $this->salesData($date);
        $sales_count = $this->salesCountData($date);
        $bookings = $this->bookingsData($date);
        $reservations = $this->reservationsData($date);

        $date = 'Date: '.Carbon::parse($date)->format('d F Y');

           
        $data = array(
            'sales' => $sales_amount,
            'sales-count' => $sales_count,
            'bookings' => $bookings,
            'reservations' => $reservations
        );

        return $this->success($data,$date);

    }

    /**
     * Get all bookings sales.
     */
    public function salesAmount($from='',$to='')
    {

        if($from != '' && $to != '')
        {
            $data = Booking::select('created_by', DB::raw('SUM(grand_total) as total'))
            ->groupBy('created_by')
            ->whereBetween('created_at',[date($from),date($to)])
            ->get();

        }else{
            $data = Booking::select('created_by', DB::raw('SUM(grand_total) as total'))
            ->groupBy('created_by')
            ->get();
        }
        
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $amount[] = $result->total;
        }

        $data = array(
            'agents' => isset($agents) ? $agents : null,
            'amount' => isset($amount) ? $amount : null,
        );

        return $this->success($data,'Sales Amount');

    }

    public function salesCount($from='',$to='')
    {
        if($from != '' && $to != '')
        {
            $data = Booking::select('created_by', DB::raw('COUNT(grand_total) as total'))
            ->groupBy('created_by')
            ->whereBetween('created_at',[date($from),date($to)])
            ->get();
        }else{
            $data = Booking::select('created_by', DB::raw('COUNT(grand_total) as total'))
            ->groupBy('created_by')
            ->get();
        }
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $amount[] = $result->total;
        }

        $data = array(
            'agents' => isset($agents) ? $agents : null,
            'amount' => isset($amount) ? $amount : null,
        );

        return $this->success($data,'Sales Count');

    }

    public function bookingsCount($from='',$to='')
    {
        if($from != '' && $to != '')
        {
            $data = Booking::select('created_by', DB::raw('COUNT(id) as total'))
            ->groupBy('created_by')
            ->whereBetween('created_at',[date($from),date($to)])
            ->get();
        }else{

            $data = Booking::select('created_by', DB::raw('COUNT(id) as total'))
            ->groupBy('created_by')
            ->get();
        }
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $booking[] = $result->total;
        }

        $data = array(
            'agents' => isset($agents) ? $agents : null,
            'bookings' => isset($booking) ? $booking : null,
        );

        return $this->success($data,'Bookings Count');

    }

    public function reservationsCount($from='',$to='')
    {

        if($from != '' && $to != '')
        {
            $data = BookingItem::select('product_type', DB::raw('COUNT(id) as total, SUM(selling_price) as total_amount'))
            ->groupBy('product_type')
            ->whereBetween('created_at',[date($from),date($to)])
            ->get();

        }else{
            $data = BookingItem::select('product_type', DB::raw('COUNT(id) as total, SUM(selling_price) as total_amount'))
            ->groupBy('product_type')
            ->get();
        }

        foreach($data as $result){
            $reserve_types = substr($result->product_type,11);

            $type[] = $reserve_types;
            $booking[] = $result->total;
            $prices[] = $result->total_amount;
        }
 
         $data = array(
            'agents' => isset($type) ? $type : null,
            'amount' => isset($booking) ? $booking : null,
            'prices' => isset($prices) ? $prices : null,         
        );

         return $this->success($data,'Reservations Count');
    }

    public function salesData($date)
    {
            $data = Booking::select('created_by', DB::raw('SUM(grand_total) as total'))
            ->groupBy('created_by')
            ->whereDate('created_at',Carbon::parse($date)->format('Y-m-d'))
            ->get();

            foreach($data as $result){
            $agents[] = $result->createdBy->name;
            $amount[] = $result->total;
            }

            $data = array(
                'agents' => isset($agents) ? $agents : null,
                'amount' => isset($amount) ? $amount : null,
            );

            return $this->success($data,'Sales Amount');

    }

    public function salesCountData($date)
    {
        
            $data = Booking::select('created_by', DB::raw('COUNT(grand_total) as total'))
            ->groupBy('created_by')
            ->whereDate('created_at','=',date($date))
            ->get();
        
    
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $amount[] = $result->total;
        }

        $data = array(
            'agents' => isset($agents) ? $agents : null,
            'amount' => isset($amount) ? $amount : null,
        );

        return $this->success($data,'Sales Count');

    }

    public function bookingsData($date)
    {
      
        $data = Booking::select('created_by', DB::raw('COUNT(id) as total'))
        ->groupBy('created_by')
        ->whereDate('created_at','=',date($date))
        ->get();
       
        foreach($data as $result){
           $agents[] = $result->createdBy->name;
           $booking[] = $result->total;
        }

        $data = array(
            'agents' => isset($agents) ? $agents : null,
            'bookings' => isset($booking) ? $booking : null,
        );

        return $this->success($data,'Bookings Count');

    }

    public function reservationsData($date)
    {

        $data = BookingItem::select('product_type', DB::raw('COUNT(id) as total, SUM(selling_price) as total_amount'))
        ->groupBy('product_type')
        ->whereDate('created_at','=',date($date))
        ->get();

        foreach($data as $result){
            $reserve_types = substr($result->product_type,11);
            $type[] = $reserve_types;
            $booking[] = $result->total;
            $prices[] = $result->total_amount;
        }
 
         $data = array(
            'agents' => isset($type) ? $type : null,
            'amount' => isset($booking) ? $booking : null,
            'prices' => isset($prices) ? $prices : null,         );

         return $this->success($data,'Reservations Count');
    }


}