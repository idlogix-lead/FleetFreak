@extends('layouts.app')

@section('wrapper')
<style>
    th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@if(isset($breadcrumbs))
    @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
@endif

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body pb-0">
                    <div class="pb-0">
                        <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                {{ __('Edit Payment') }}
                            </h3>
                        </div>
                        @php
                            $link = 'payment_window.edit';
                        @endphp
                        @include('payment-window.partials.filter')
                    </div> 
                </div>
            </div>
            @if($payment_header->paymentLines->isNotEmpty())
        </div>
            <div class="card">
                <div class="card-body">
                    <div class="pb-4">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">{{ __('Payment Window') }}</h3>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('payment_window.update', $payment_header->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="table-responsive">
                            <table id='order' class="table table-hover fixed-table">
                                <thead class="thead table-light">
                                    <tr>
                                        <th>No #</th>
                                        <th>Order No</th>
                                        <th>Order Detail No</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payment_header->paymentLines as $index => $line)
                                        <tr id="order_row{{ $index + 1 }}" data-row-index="{{ $index }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $line->order->order_no }}</td>
                                            <td>{{ $line->order_detail_no }}</td>
                                            <td class="order-rate">{{ $line->amount }}</td>
                                            <td>
                                                <input type="hidden" name="order_id[]" value="{{ $line->order_id }}">
                                                <input type="hidden" class="order-id" value="{{ $line->order->id }}">
                                                <input type="hidden" class="agent-id" value="{{ $line->order->partner_business->id }}">
                                                <input type="checkbox" name="order_checked[{{ $index }}]" class="order-checkbox" value="{{ $line->amount }}" {{ $line->checked ? 'checked' : '' }}>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" class="form-control" value="{{ $payment_header->date }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $payment_header->description }}</textarea>
                        </div>

                        <input type="hidden" id="total_amount" name="total_amount" value="{{ $payment_header->total_amount }}">
                        
                        <button type="submit" class="btn btn-outline-success float-end">{{ __('Update Payment') }}</button>
                    </form>
                </div>
            </div>
            @else
            <div class="card-body">
                <p> No orders found for the selected filters</p> 
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const checkboxes = $('.order-checkbox');
        const totalAmountElement = $('#total-amount');
        const totalAmountInput = $('#total_amount');
        let totalAmount = {{ $paymentHeader->total_amount ?? 0 }};

        checkboxes.change(function() {
            const rate = parseFloat($(this).val());
            if (this.checked) {
                totalAmount += rate;
            } else {
                totalAmount -= rate;
            }
            totalAmountElement.text(totalAmount.toFixed(2));
            totalAmountInput.val(totalAmount.toFixed(2)); // Update the hidden input
        });
    });
</script>
@endsection
