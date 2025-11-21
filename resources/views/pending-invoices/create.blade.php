@extends('layouts.app')

@section('wrapper')
<style>
    th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    @media print {
        .no-print {
            display: none !important;
        }
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
                                {{ __('Create Pending Invoices') }}
                            </h3>
                        </div>
                        @php
                            $link = 'pending_rental_invoices.create';
                        @endphp
                        @include('pending-invoices.partials.filter')
                    </div>
                </div>
            </div>
            @if($orders->isNotEmpty())
        </div>
            <div class="card">
                <div class="card-body">
                    <div class="pb-4">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">{{ __('Pending Invoices') }}</h3>
                            {{-- <button type="button" class="btn btn-outline-success pay-now-btn float-end" 
                              data-bs-toggle="modal" data-bs-target="#payNowModal">
                              Save Now
                            </button>
                            <button type="button" class="btn btn-outline-secondary save-draft-btn float-end"
                            data-bs-toggle="modal" data-bs-target="#payNowModal">
                            Save as Draft
                          </button> --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id='order' class="table table-hover fixed-table">
                            <thead class="thead table-light">
                                <tr>
                                    <th>No #</th>
                                    <th>Order No</th>
                                    <th>Order Detail No</th>
                                    <th>Customer</th>
                                    <th>Trip Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row_id = 1;
                                    $row_index = $row_id - 1;
                                    $i=0;
                                @endphp
                                @foreach ($orders as $order)
                                    <tr id="order_row{{ $row_id }}" data-row-index="{{ $row_index }}">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $order->order->order_no }}</td>
                                        @if(isset($payment_header))
                                        <td>{{ $order->order_detail_id }}</td>
                                        @else
                                        <td>{{ $order->id }}</td>
                                        @endif
                                        <td>{{Str::title($order->order->partner_customer->name)}}</td>
                                        <td>{{Str::title($order->order->trip_type)}}</td>
                                        <td>{{Str::title($order->date)}}</td>
                                        <td>{{Str::title($order->end_date)}}</td>
                                        
                                        <td class="order-rate">{{ $order->rate ?$order->rate: $order->driver_rate }}</td>
                                        <td>
                                            <input type="hidden" name="order_checked[{{ $row_index }}]" value='0'>
                                            <input type="hidden" class="order-id" value="{{ $order->order->id ?? null }}">
                                            <input type="hidden" class="agent-id" value="{{ $order->order->partner_business->id??null }}">
                                            <input type="hidden" class="customer-id" value="{{ $order->order->partner_customer->id??null }}">
                                            <input type="checkbox"  name="order_checked[{{ $row_index }}]" class="order-checkbox" value="{{ $order->rate ?$order->rate: $order->total_amount  }}">
                                        </td>
                                    </tr>
                                    @php
                                        $row_id++;
                                        $row_index = $row_id - 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                        {{-- {!! $orders->links() !!} --}}
                    </div>
                    {{-- <button type="button" class="btn btn-outline-success pay-now-btn float-end" 
                    data-bs-toggle="modal" data-bs-target="#payNowModal">
                    Create Invoice
                    </button> --}}
                    {{-- <button type="button" class="btn btn-outline-secondary save-draft-btn float-end mx-2"
                    data-bs-toggle="modal" data-bs-target="#payNowModal">
                    Save as Draft
                    </button> --}}
                    {{-- <div class="total-amount">
                        <h4>Total Amount: <span id="total-amount">0</span></h4>
                    </div> --}}
                </div>
            </div>
            @else
            <div class="card-body">
                <p> Please select the customer from the above dropdown</p> 
            </div>
            @endif
        </div>
    
</div>

@include('pending-invoices.partials.model')

<script>
    $(document).ready(function() {
        const checkboxes = $('.order-checkbox');
        const totalAmountElement = $('#total-amount');
        const totalAmountInput = $('#total_amount');
        const modalTotalAmountInput = $('#modalTotalAmount');
        const hiddenFieldsContainer = $('#hidden-fields-container');
        let totalAmount = 0;

        checkboxes.change(function() {
            const rate = parseFloat($(this).val());
            if (this.checked) {
                totalAmount += rate;
            } else {
                totalAmount -= rate;
            }
            totalAmountElement.text(totalAmount.toFixed(2));
            totalAmountInput.val(totalAmount.toFixed(2)); // Update the hidden input
            modalTotalAmountInput.val(totalAmount.toFixed(2)); // Update the hidden input in the modal
        });

        function handleSave(action) {
            // Clear previous hidden fields
            hiddenFieldsContainer.empty();
            // Validate if at least one checkbox is checked
            let isChecked = false;
            checkboxes.each(function() {
                if (this.checked) {
                    isChecked = true;
                    return false; // break the loop
                }
            });

            if (!isChecked) {
                msgboxbox.show("You must check at least one box.",'error', null);

            
                event.preventDefault(); // Prevent the modal from showing
                return;
            }

            // Populate modal hidden fields with filter data
            $('#modalCustomer').val($('#customer_id').val()); // Send customer ID instead of name
            $('#modalDate').val($('#date').val());
            $('#modalDescription').val($('#description').val());
            $('#modalTotalAmount').val(totalAmount.toFixed(2));

            // Add hidden fields for each checked checkbox
            checkboxes.each(function() {
                if (this.checked) {
                    const rowIndex = $(this).attr('name').match(/\d+/)[0];
                    const orderNo = $(`#order_row${parseInt(rowIndex) + 1} td:nth-child(2)`).text().trim();
                    const orderDetailNo = $(`#order_row${parseInt(rowIndex) + 1} td:nth-child(3)`).text().trim();
                    const amount = $(`#order_row${parseInt(rowIndex) + 1} .order-rate`).text().trim();
                    const orderId = $(`#order_row${parseInt(rowIndex) + 1} .order-id`).val();
                    const agentId = $(`#order_row${parseInt(rowIndex) + 1} .agent-id`).val();
                    const customerId = $(`#order_row${parseInt(rowIndex) + 1} .customer-id`).val();

                    const orderNoInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'order_no[]',
                        value: orderNo
                    });
                    hiddenFieldsContainer.append(orderNoInput);

                    const orderDetailNoInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'order_detail_no[]',
                        value: orderDetailNo
                    });
                    hiddenFieldsContainer.append(orderDetailNoInput);

                    const amountInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'amount[]',
                        value: amount
                    });
                    hiddenFieldsContainer.append(amountInput);

                    const orderIdInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'order_id[]',
                        value: orderId
                    });
                    hiddenFieldsContainer.append(orderIdInput);
                    const agentIdInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'agent_id[]',
                        value: agentId
                    });
                    hiddenFieldsContainer.append(agentIdInput);
                    const customerIdInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'customer_id[]',
                        value: customerId
                    });
                    hiddenFieldsContainer.append(customerIdInput);
                }
            });

            // Add hidden field for action
            const actionInput = $('<input>').attr({
                type: 'hidden',
                name: 'action',
                value: action
            });
            hiddenFieldsContainer.append(actionInput);
        }

        $('.pay-now-btn').click(function() {
            handleSave('completed');
           

        });

        $('.save-draft-btn').click(function() {
            handleSave('draft');
        });
    });
</script>
@endsection
