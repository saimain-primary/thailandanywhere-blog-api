<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @page {
            margin: 0;
            /* margin-top: 300px;
            padding: 10px 40px */
        }

        .page-break {
            page-break-after: always;
        }

        body {
            margin: 0px;
            font-family: 'Poppins', sans-serif;
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;

        }

        .header-title {
            font-size: 14px;
        }

        .header-desc {
            font-size: 12px;
            display: block;
            margin-bottom: 4px;
            font-weight: normal;
        }

        .header-heading {
            color: #ff5b00;
            font-size: 18px;
            font-weight: normal;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }


        .header-table th,
        .header-table td {
            /* border: 1px solid black; */
            text-align: left
        }

        .header-table th {
            font-weight: normal;
            font-size: 10px;
            color: #ff5b00
        }

        .header-table td {
            font-weight: normal;
            font-size: 10px;
        }

        /**
            * Define the width, height, margins and position of the watermark.
            **/
        #watermark {
            position: fixed;
            bottom: 0px;
            left: 0px;
            /** The width and height may change
                    according to the dimensions of your letterhead
                **/
            /** Your watermark should be behind every content**/
            z-index: -1000;
        }

        .break-before {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="{{ public_path() . '/assets/attraction_template.jpg' }}" height="100%" width="100%" />
    </div>
    
    <div>
        <div style="margin-top: 170px;
        padding: 10px 40px" id="wrap">
            <h3 class="header-heading ">{{Carbon\Carbon::now()->format('d F Y')}}</h3>

            <p>Dear Sir, </p>

            <p>Thank you very much for your booking. Please see the information below for confirmation of the {{$data->product->name}}.

            </p><br/>

            <div class="one">
                Name of Attraction: 
            </div>
            <div class="two info">{{$data->product->name}}</div><br/><br/><br/>

            <div class="one">Agent Name:</div>
            <div class="two info">TH Anywhere Co.Ltd.</div><br/><br/>
            <div class="one">Reservation Date:</div>
            <div class="two info">{{Carbon\Carbon::parse($data->created_at)->format('d F Y')}}</div><br/><br/>
            @if($data->payment_method)
                <div class="one space">Payment Method:</div>
                <div class="two space info">{{$data->payment_method}}</div><br/><br>
            @endif<div class="one">Customer Name:</div>
            <div class="two info">{{$data->booking->customer->name}}</div><br><br/>
            <div class="one">Passport Number:</div>
            <div class="two info">{{$data->customer_passports ? $data->customer_passports : '-'}}</div><br/><br/><br/><br/>
            <div class="one">Name of Attraction:</div>
            <div class="two info">{{$data->product->name}}</div><br/><br/>

            <div class="one ">Type of Ticket:</div>
            <div class="two  info">{{$data->variation->name}}</div><br/><br/>

            <div class="one">Service Date:</div>
            <div class="two info">{{Carbon\Carbon::parse($data->service_date)->format('d F Y')}}</div><br/><br/>

            <div class="one">No. of Person:</div>
            <div class="two info">{{count($customers)}}</div><br/><br/>

            <div class="one">Thanks for your booking.</div>




            
        </div>
    </div>
</body>

</html>
