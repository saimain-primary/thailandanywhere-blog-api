<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Http\Resources\AdminResource;
use App\Http\Controllers\Controller;
use App\Traits\HttpResponses;
use App\Http\Resources\BookingResource;


use DB;
use DateTime;
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
            'sales_count' => $sales_count,
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
            'sales_count' => $sales_count,
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

        $query = Booking::query();

        if($from != '' && $to != '')
        {
            $query->whereBetween('created_at',[date($from),date($to)]);

        }


        $data = $query->get();

        $results = BookingResource::collection($data);

        $items=[];

        foreach($results as $key => $res)
        {
            foreach($res->items as $res1){
                $reserve_types = substr($res1->product_type,11);

                if($reserve_types == 'Hotel'){

                    $datetime1 = new DateTime($res1->checkin_date);
                    $datetime2 = new DateTime($res1->checkout_date);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');

                    $price = $res1->quantity * $res1->selling_price * $days;

                }else{

                    $price = $res1->quantity * $res1->selling_price;

                }
                
                $one[] = [
                    'product_type' => $reserve_types,
                    'price' => $price
                ];
               
                $items[] = array(
                        'product_type' => $reserve_types,
                        'prices' => $price
                );
            }
        }

        $count_bookings = array_count_values(array_column($items, 'product_type'));

        foreach($count_bookings as $value)
        {
            $booking[] = $value;
        }

        $new_array = array();
        foreach ($one as $value) {
            if(array_key_exists($value['product_type'],$new_array))
                $value['price'] += $new_array[$value['product_type']]['price'];
            $new_array[$value['product_type']] = $value;
        }

        foreach($new_array as $res)
        {
            $type[] = $res['product_type'];
            $prices[] = $res['price'];

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

        $query = Booking::query();

        $query->whereDate('created_at', date($date));

        $data = $query->get();

        $results = BookingResource::collection($data);

        $items=[];

        foreach($results as $key => $res)
        {
            foreach($res->items as $res1){
                $reserve_types = substr($res1->product_type,11);

                if($reserve_types == 'Hotel'){

                    $datetime1 = new DateTime($res1->checkin_date);
                    $datetime2 = new DateTime($res1->checkout_date);
                    $interval = $datetime1->diff($datetime2);
                    $days = $interval->format('%a');

                    $price = $res1->quantity * $res1->selling_price * $days;

                }else{

                    $price = $res1->quantity * $res1->selling_price;

                }
                
                $one[] = [
                    'product_type' => $reserve_types,
                    'price' => $price
                ];
               
                $items[] = array(
                        'product_type' => $reserve_types,
                        'prices' => $price
                );
            }
        }

        $count_bookings = array_count_values(array_column($items, 'product_type'));

        foreach($count_bookings as $value)
        {
            $booking[] = $value;
        }

        $new_array = array();
        foreach ($one as $value) {
            if(array_key_exists($value['product_type'],$new_array))
                $value['price'] += $new_array[$value['product_type']]['price'];
            $new_array[$value['product_type']] = $value;
        }

        foreach($new_array as $res)
        {
            $type[] = $res['product_type'];
            $prices[] = $res['price'];

        }
         $data = array(
            'agents' => isset($type) ? $type : null,
            'amount' => isset($booking) ? $booking : null,
            'prices' => isset($prices) ? $prices : null,
        );

         return $this->success($data,'Reservations Count');
    }

}
