<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        body {
            font-family: sans-serif
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
            color: #5897fb;
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
            background: #cfe2ff;
            color: #5292fa;
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
    </style>
</head>

<body>
    <img width="100px" height="100px" style="margin-bottom: 10px" src="{{ asset('assets/logo.jpg') }}" />
    <h1 class="header-title">Thailand Anywhere</h1>
    <span class="header-desc">39 Chaospanraya . Jewelry Building Floor 4, spanhaya Thai Road, Thanon Phaya Thai
        Sub-District,
        Ratchathew</span>
    <span class="header-desc">Bangkok TH</span>
    <span class="header-desc">+66 943045244</span>
    <span class="header-desc">ceo@thanywhere.com</span>

    <div>
        <h3 class="header-heading">Invoice</h3>
        <table class="header-table">
            <tbody>
                <tr>
                    <th style="width:70%">BILL TO</th>
                    <th>INVOICE</th>
                    <td>{{ $data->crm_id }}</td>
                </tr>
                <tr>
                    <td style="width:70%">{{ $data->bill_to }}</td>
                    <th>Date</th>
                    <td>{{ $data->created_at->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <td style="width:70%"></td>
                    <th>TERM</th>
                    <td></td>
                </tr>
                <tr>
                    <td style="width:70%"></td>
                    <th>DUE DATE</th>
                    <td>{{ $data->balance_due_date }}</td>
                </tr>
                {{-- @foreach ($data as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->name }}</td>
                        <!-- Add more columns as needed -->
                    </tr>
                @endforeach --}}
            </tbody>
        </table>
        <table class="body-table">
            <tbody>
                <tr>
                    <th>DATE</th>
                    <th>SERVICE</th>
                    <th style="max-width:140px">DESCRIPTION</th>
                    <th>QTY</th>
                    <th>RATE</th>
                    <th>AMOUNT</th>
                </tr>
                @foreach ($data->items as $row)
                    <tr>
                        <td>{{ $row->service_date }}</td>
                        <td style="max-width: 100px">{{ $row->product->name }}</td>
                        <td style="max-width: 120px">{{ $row->comment }}</td>
                        <td>{{ $row->quantity }}</td>
                        <td>{{ number_format((float) $row->selling_price) }}</td>
                        <td>{{ number_format((float) $row->selling_price * (float) $row->quantity) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table class="footer-table">
            <tbody>
                <tr>
                    <td>Thank you for booking with Thailand Anywhere. We are with you every step of the way.</td>
                    <td>SUB TOTAL</td>
                    <td style="font-size:14px;">
                        {{ number_format($data->sub_total) }}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>DISCOUNT</td>
                    <td style="font-size:14px;">
                        {{ $data->discount }}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>DEPOSIT</td>
                    <td style="font-size:14px;">
                        {{ $data->deposit }}
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>BALANCE DUE</td>
                    <td style="font-weight: bold; font-size:14px;">THB
                        {{ number_format($data->grand_total) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
