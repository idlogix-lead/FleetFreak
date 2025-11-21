<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header h1 {
            font-size: 24px;
            color: #0033cc;
            margin: 0;
        }
        .header img {
            height: 50px;
        }
        .table th {
            background-color: #f5f5f5;
        }
        .footer .total {
            font-size: 18px;
            font-weight: bold;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            font-style: italic;
        }
        .row div {
            padding: 5px;
        }
        @media print {
            .text-right {
                text-align: right !important;
            }
            .container {
                width: 100%;
            }
            
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="header d-flex justify-content-between align-items-center">
            <h1><strong>INVOICE</strong></h1>
            <img src="{{ asset('assets/images/login-fleetfreak-logo.png') }}" alt="Logo">
        </div>

        <div class="row mt-4 mx-1">
            <div class="col-md-12 col-sm-12">
                <p><strong>Fleet freak Inc.</strong></p>
                <p>1912 Harvest Lane</p>
                <p>New York, NY 12210</p>
            </div>
        </div>
           
        

        <div class="row mt-4 mx-1">
            <div class="col-md-4 col-sm-4">
                <p><strong>Invoice #:</strong> {{ $orders->invoice_no ?? 'N/A' }}</p>
                <p><strong>Invoice Date:</strong> {{ $orders->invoice_date ?? now()->format('d/m/Y') }}</p>
                <p><strong>Due Date:</strong> {{ $orders->end_date ?? now()->addDays(15)->format('d/m/Y') }}</p>
            </div>
            <div class="col-md-4 col-sm-4">
                <p><strong>Bill To:</strong></p>
                <p>{{ Str::title($orders->order->partner_customer->name) ?? 'N/A' }}</p>
                <p>{{ $orders->order->partner_customer->address1 ?? 'N/A' }}</p>
                <p>{{ $orders->order->partner_customer->city ?? 'N/A' }}</p>
            </div>
            <div class="col-md-4 col-sm-4">
                <p><strong>Ship To:</strong></p>
                <p>{{ Str::title($orders->order->partner_business->name) ?? 'N/A' }}</p>
                <p>{{ $orders->order->partner_business->address1 ?? 'N/A' }}</p>
                <p>{{ $orders->order->partner_business->city ?? 'N/A' }}</p>
            </div>
        </div>

        <table class="table table-sm mt-4">
            <thead>
                <tr>
                    <th>No #</th>
                    <th>Order No</th>
                    <th>Trip Type</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i =0;
                @endphp
                {{-- @foreach($orders as $detail) --}}
                <tr>
                    
                    <td>{{ ++$i }}</td>
                    <td>{{ $orders->order->order_no }}</td>
                    <td>{{ Str::title($orders->order->trip_type) }}</td>
                    <td>{{ $orders->date}}</td>
                    <td>{{ $orders->end_date}}</td>
                    <td>{{ $orders->rate }}</td>
                </tr>
                {{-- @endforeach --}}
            </tbody>
        </table>

        <div class="footer mt-4">
            <p><strong>Subtotal:</strong> ${{ number_format($orders->rate, 2) }}</p>
            {{-- <p><strong>Sales Tax ({{ $orders->tax_rate }}%):</strong> ${{ number_format($orders->tax_amount, 2) }}</p> --}}
            <p class="total"><strong>TOTAL:</strong> ${{ number_format($orders->rate, 2) }}</p>
        </div>

        <div class="signature mt-5">
            <p>Thank you!</p>
            <p>______________________</p>
            <p>Fleet Freak</p>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
