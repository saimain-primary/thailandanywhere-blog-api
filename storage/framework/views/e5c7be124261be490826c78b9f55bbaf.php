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
    </style>
</head>

<body>
    <div id="watermark">
        <img src="<?php echo e(public_path() . '/assets/template.jpg'); ?>" height="100%" width="100%" />
    </div>
    <div>
        <div style="margin-top: 300px;
        padding: 10px 40px">
            <h3 class="header-heading">Invoice</h3>
            <table class="header-table">
                <tbody>
                    <tr>
                        <th style="width:70%">BILL TO</th>
                        <th>INVOICE</th>
                        <td><?php echo e($data->invoice_number); ?></td>
                    </tr>
                    <tr>
                        <td style="width:70%"><?php echo e($data->customer->name); ?> / <?php echo e($data->customer->phone_number); ?></td>
                        <th>CRMID</th>
                        <td><?php echo e($data->crm_id); ?></td>
                    </tr>
                    <?php if($data->is_past_info): ?>
                        <tr>
                            <td style="width:70%"></td>
                            <th>PAST CRMID</th>
                            <td><?php echo e($data->past_crm_id); ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th style="width:70%">DATE</th>
                        <th>TERM</th>
                    </tr>
                    <tr>
                        <td style="width:70%"><?php echo e($data->created_at->format('d-m-Y')); ?></td>
                        <th>DUE DATE</th>
                        <td><?php echo e($data->balance_due_date); ?></td>
                    </tr>
                </tbody>
            </table>
            <table class="body-table" style="max-height: 100px !important;">
                <tbody>
                    <tr>
                        <th>SERVICE DATE</th>
                        <th>SERVICE</th>
                        <th style="max-width:140px">DESCRIPTION</th>
                        <th>QTY</th>
                        <th>RATE</th>
                        <th>AMOUNT</th>
                    </tr>
                    <?php $__currentLoopData = $data->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($index == 4): ?>
            </table>
            <div class="break-before"></div>
            <table class="body-table" style="max-height: 100px !important; margin-top:300px;">
                <tbody>
                    <tr>
                        <th>SERVICE DATE</th>
                        <th>SERVICE</th>
                        <th style="max-width:140px">DESCRIPTION</th>
                        <th>QTY</th>
                        <th>RATE</th>
                        <th>AMOUNT</th>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td><?php echo e($row->service_date); ?></td>
                        <td style="max-width: 100px"><?php echo e($row->product->name); ?> </br>
                            <?php if($row->product_type === 'App\Models\Inclusive'): ?>
                                <?php if($row->product->privateVanTours): ?>
                                    <?php $__currentLoopData = $row->product->privateVanTours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pvt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($pvt->product->name); ?> </br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if($row->product->groupTours): ?>
                                    <?php $__currentLoopData = $row->product->groupTours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($gt->product->name); ?> </br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if($row->product->airportPickups): ?>
                                    <?php $__currentLoopData = $row->product->airportPickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ap): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($ap->product->name); ?> </br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                                <?php if($row->product->entranceTickets): ?>
                                    <?php $__currentLoopData = $row->product->entranceTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $et): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php echo e($et->product->name); ?> </br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td style="max-width: 120px"><?php echo e($row->comment); ?></td>
                        <td><?php echo e((int) $row->quantity * (int) ($row->days ? $row->days : 1)); ?></td>
                        <td><?php echo e(number_format((float) $row->selling_price)); ?></td>
                        <td><?php echo e(number_format($row->amount)); ?></td>
                    </tr>
                    <?php if($index == 4): ?>
                        <tbody />
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <table class="footer-table">
                <tbody>
                    <tr>
                        <td>Thank you for booking with Thailand Anywhere. We are with you every step of the way.</td>
                        <td>SUB TOTAL</td>
                        <td style="font-size:14px;">
                            <?php echo e(number_format($data->sub_total)); ?> THB
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>DISCOUNT</td>
                        <td style="font-size:14px;">
                            <?php echo e($data->discount); ?> THB
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td style="font-size:14px;">
                            <?php echo e($data->sub_total - $data->discount); ?> THB
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>DEPOSIT</td>
                        <td style="font-size:14px;">
                            <?php echo e($data->deposit); ?> THB
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>BALANCE DUE</td>
                        <td style="font-weight: bold; font-size:14px;">
                            <?php echo e(number_format($data->sub_total - $data->discount - $data->deposit)); ?> THB
                        </td>
                    </tr>
                    <?php if($data->money_exchange_rate): ?>
                        <tr>
                            <td></td>
                            <td>EXCHANGE RATE</td>
                            <td style="font-size:14px;">
                                <?php echo e($data->money_exchange_rate); ?>

                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>DEPOSIT IN <?php echo e($data->payment_currency); ?></td>
                            <td style="font-size:14px;">
                                <?php echo e(number_format($data->deposit * $data->money_exchange_rate)); ?>

                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>BALANCE DUE (<?php echo e($data->payment_currency); ?>)</td>
                            <td style="font-weight: bold; font-size:14px;">
                                <?php if($data->deposit === 0 || $data->deposit === 'null'): ?>
                                    <?php if($data->payment_currency === 'USD'): ?>
                                        <?php echo e(number_format((float) ($data->sub_total - $data->discount) / ($data->money_exchange_rate ? (float) $data->money_exchange_rate : 1), '2', '.', '')); ?>

                                    <?php else: ?>
                                        <?php echo e(number_format((float) ($data->sub_total - $data->discount) * $data->money_exchange_rate ? (float) $data->money_exchange_rate : 1, '2', '.', '')); ?>

                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php if($data->payment_currency === 'USD'): ?>
                                        <?php echo e(number_format((float) ($data->sub_total - $data->discount - $data->deposit) / ($data->money_exchange_rate ? (float) $data->money_exchange_rate : 1), 2, '.', '')); ?>

                                    <?php else: ?>
                                        <?php echo e(number_format((float) ($data->sub_total - $data->discount - $data->deposit) * ($data->money_exchange_rate ? (float) $data->money_exchange_rate : 1), 2, '.', '')); ?>

                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php echo e($data->payment_currency); ?>

                            </td>
                        </tr>

                    <?php endif; ?>
                    <tr>
                        <td></td>
                        <td>PAYMENT STATUS</td>
                        <?php if($data->payment_status === 'not_paid'): ?>
                            <td style="font-weight: bold; font-size:14px; color:red">
                                <?php echo e(ucwords(str_replace('_', ' ', $data->payment_status))); ?>

                            </td>
                        <?php endif; ?>
                        <?php if($data->payment_status === 'partially_paid'): ?>
                            <td style="font-weight: bold; font-size:14px; color:#ff5733">
                                <?php echo e(ucwords(str_replace('_', ' ', $data->payment_status))); ?>

                            </td>
                        <?php endif; ?>
                        <?php if($data->payment_status === 'fully_paid'): ?>
                            <td style="font-weight: bold; font-size:14px; color: green">
                                <?php echo e(ucwords(str_replace('_', ' ', $data->payment_status))); ?>

                            </td>
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
        </div>


    </div>
</body>

</html>
<?php /**PATH /var/www/projects/thanywheresale-api/resources/views/pdf/booking_receipt.blade.php ENDPATH**/ ?>