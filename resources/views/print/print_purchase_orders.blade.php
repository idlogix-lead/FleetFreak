<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>Purchase Order</title>
    <style>
        .right-align {
            text-align: right;
            margin-left: 20px; /* Adjust this value as needed */
        }
        body { font-family: Arial, sans-serif; }
        .header { text-align: left; margin-bottom: 10px; margin-left: 10px; }
        .header h2 { margin: 0; }
        .header p { margin: 5px 0; font-size: 12px; }
        .bordered-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .bordered-table td, .bordered-table th { border: 1px solid black; padding: 8px; font-size: 12px; text-align: left; }
        .left-section { width: 50%; vertical-align: top; }
        .right-section { width: 50%; vertical-align: top; }
        .bold-text { font-weight: bold; }
        .table-container { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-container td, .table-container th { border: 1px solid black; padding: 5px; font-size: 12px; text-align: center; }
        .total { font-weight: bold; text-align: right; }
        .details-container {
            display: flex;
            justify-content: space-between; /* Ensures proper spacing */
            width: 100%;
        }

        .left-details, .right-details {
            width: 48%; /* Adjusts width for alignment */
        }

        .bold-text {
            font-weight: bold;
        }
        .header-image img {
            height: 50px;
        }
    </style>
</head>
<body>

    <div class="header">
        {{-- <h2>STARLET</h2> --}}
        <div class="header-image">
            <img src="{{ asset('assets/images/login-fleetfreak-logo.png') }}" alt="Logo">
        </div>
        <p>25-km Lahore Sheikupura Road, Lahore, Pakistan<br>
        Phone: +92-42 3761 2020</p>
        <h3 style="text-align: center;">PURCHASE ORDER</h3>
    </div>
    <table class="bordered-table">
        <tr>
            <td class="left-section">
                <p><span class="bold-text">Business Partner Name:</span> {{ Str::title($order->partner_business->name) ?? '---' }}</p>
                <p><span class="bold-text">Address:</span> {{ $order->partner_business->partnerLocation->address1 ?? '---' }}</p>
                <p><span class="bold-text">Phone:</span> {{ $order->partner_business->phone_no ?? '---' }}</p>
            </td>
            <td class="right-section">
                <div class="details-container">
                    <div class="left-details">
                        <p><span class="bold-text">Dated Ordered:</span> {{ \Carbon\Carbon::parse($order->date_ordered)->format('M d, Y') }}</p>
                        <p><span class="bold-text">Document No:</span> {{ $order->order_no }}</p>
                        <p><span class="bold-text">Date Promised:</span> {{ \Carbon\Carbon::parse($order->date_promised)->format('M d, Y') }}</p>
                        <p><span class="bold-text">Payment Terms:</span> {{ $order->payment_term ?? '---' }}</p>
                    </div>
                    
                    <div class="right-details">
                        <p><span class="bold-text">Warehouse:</span> {{ Str::title($order->wareHouse->name) ?? '---' }}</p>
                        <p><span class="bold-text">Order Ref:</span> {{ $order->po_reference ?? '---' }}</p>
                        <p><span class="bold-text">Price List:</span> {{ $order->priceList ? Str::title($order->priceList->name) : '---' }}</p>
                        <p><span class="bold-text">Currency:</span> {{ $order->currency ?? '---' }}</p>
                    </div>
                </div>
            </td>
            
            {{-- <td class="right-section">
                <p><span class="bold-text">Warehouse:</span> {{ $order->warehouse_id ?? '---' }}</p>
                <p><span class="bold-text">Order Ref:</span> {{ $order->po_ref ?? '---' }}</p>
                <p><span class="bold-text">Price List:</span> {{ $order->price_list ?? '---' }}</p>
                <p><span class="bold-text">Currency:</span> {{ $order->currency ?? '---' }}</p>
            </td> --}}
        </tr>
    </table>

    <table class="table-container">
        <tr>
            <th>Sr #</th>
            <th>Product</th>
            <th>Unit</th>
            <th>QTY</th>
            <th>Rate</th>
            <th>Tax</th>
            <th>Tax Value</th>
            <th>Line Amount</th>
            <th>Total Amount</th>
        </tr>
        @php $grandTotal = 0; @endphp
        @foreach($order_details as $index => $detail)
        @php 
            $lineTotal = $detail->total_line_amount ?? 0;
            $grandTotal += $lineTotal;
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail->product->name ?? '---' }}</td>
            <td>{{ $detail->unitMeasure->name ?? '---' }}</td>
            <td>{{ $detail->quantity ?? '---' }}</td>
            <td>{{ $detail->rate ?? '---' }}</td>
            <td>{{ $detail->tax ?? '---' }}</td>
            <td>{{ $detail->tax_value ?? '---' }}</td>
            <td>{{ $detail->line_amount ?? '---' }}</td>
            <td>{{ number_format($lineTotal, 2) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="8" class="total">Total</td>
            <td class="total">{{ number_format($grandTotal, 2) }}</td>
        </tr>
    </table>

    <p><strong>Total Amount in Words:</strong> {{ ucwords(\NumberFormatter::create('en', \NumberFormatter::SPELLOUT)->format($grandTotal)) }} only</p>
    <h4><strong>Terms & Conditions</strong></h4>
    <ul style="font-size: 12px;">
        <li>Freight control & Loading will be paid by SUPPLIER.</li>
        <li>All the Materials should be on "REACH" Compliance.</li>
        <li>In case material fails in testing, the supplier will be responsible.</li>
        <li>Compliance of the delivery date is necessary. 1% will be charged daily on late deliveries.</li>
        <li>Please mention our Purchase Order No. on all your delivery challans and invoices.</li>
        <li>Delivery of Goods must be made strictly in accordance with Purchase Order. If the goods are delivered in damaged conditions and/or not in accordance with the specification mentioned in this Purchase Order, they shall be rejected and returned by us.</li>
        <li>Payment will be made on the basis of the approved order quantity or actual quantity received, whichever is lower and subject to quality approval. Our record will be considered final and decisive at this point.</li>
        <li>Payment will be subject to deduction of Income Tax at Source at the prevailing rate if applicable.</li>
        <li>Payment will be made within the specified period of submission of Invoice along with acknowledgment of goods.</li>
    </ul>

    <p><strong>Note:</strong> <span style="text-decoration: underline;">THIS IS A SYSTEM-GENERATED DOCUMENT AND DOES NOT REQUIRE ANY SIGNATURE AND STAMP.</span></p>


</body>
</html>
