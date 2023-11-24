<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Google Font: Source Sans Pro -->
    

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
            color: #6f6f6f
        }

        .header-table td {
            font-weight: normal;
            font-size: 10px;
        }

        .body-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border-bottom: 1px dashed black;
        }

        .body-table th,
        .body-table td {
            text-align: left
        }

        .body-table th {
            padding: 10px 5px;
            background: #ffe5d7;
            color: #ff5b00;
            font-size: 10px;
            font-weight: normal;
            font-family: sans-serif;
        }

        .body-table td {
            padding: 10px;
            font-size: 10px;
            font-family: sans-serif;
            font-weight: normal;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .footer-table tr {
            font-size: 10px;
            font-weight: normal;
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
        .one,
        .two,
        .three{
        display: inline-block;
        }

        .left-wrapper,
        .right-wrapper {
        display: inline-block;
        }

        .two{
            position: absolute;
            left: 19em;
        }

        .right-wrapper {
        float: right;
        }

        * {
            line-height: 1.5;
        }

        .info{
            color: #FF5D01;
        }
    </style>
</head>

<body>
    <div id="watermark">
        <img src="<?php echo e(public_path() . '/assets/template.jpg'); ?>" height="100%" width="100%" />
    </div>
    <div>
        <div style="margin-top: 170px;
        padding: 10px 40px" id="wrap">
            <h3 class="header-heading "><?php echo e(Carbon\Carbon::now()->format('d F Y')); ?></h3>

            <p>Dear Sir, </p>

            <p>Thank you very much for your booking. Please see the information below for confirmation of the <?php echo e($data->product->name); ?>.

            </p><br/>

            <div class="one">
                Name of Attraction: 
            </div>
            <div class="two info"><?php echo e($data->product->name); ?></div><br/><br/><br/>

            <div class="one">Agent Name:</div>
            <div class="two info">TH Anywhere Co.Ltd.</div><br/><br/>
            <div class="one">Reservation Date:</div>
            <div class="two info"><?php echo e(Carbon\Carbon::parse($data->created_at)->format('d F Y')); ?></div><br/><br/>
            <?php if($data->payment_method): ?>
                <div class="one space">Payment Method:</div>
                <div class="two space info"><?php echo e($data->payment_method); ?></div><br/><br>
            <?php endif; ?><div class="one">Customer Name:</div>
            <div class="two info"><?php echo e($data->booking->customer->name); ?></div><br><br/>
            <div class="one">Passport Number:</div>
            <div class="two info"><?php echo e($data->customer_passports ? $data->customer_passports : '-'); ?></div><br/><br/><br/><br/>
            <div class="one">Name of Attraction:</div>
            <div class="two info"><?php echo e($data->product->name); ?></div><br/><br/>

            <div class="one ">Type of Ticket:</div>
            <div class="two  info"><?php echo e($data->variation->name); ?></div><br/><br/>

            <div class="one">Service Date:</div>
            <div class="two info"><?php echo e(Carbon\Carbon::parse($data->service_date)->format('d F Y')); ?></div><br/><br/>

            <div class="one">No. of Person:</div>
            <div class="two info"><?php echo e(count($customers)); ?></div><br/><br/>

            <div class="one">Thanks for your booking.</div>




            

            
            

            
        </div>


    </div>
</body>

</html>
<?php /**PATH /var/www/projects/thanywheresale-api/resources/views/pdf/reservation_receipt.blade.php ENDPATH**/ ?>