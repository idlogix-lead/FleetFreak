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
                                {{-- for seperate view for agent and admin --}}
                                @if(auth()->user()->actor_id==2)
                                {{ __('Agent Search') }}
                                @else
                                {{ __('Search') }}
                                @endif
                            </h3>
                        </div>
                        
                        @php
                            $link = 'ledgers.index';
                        @endphp
                        
                        @include('ledgers.partials.filter')

                    </div>
                </div>
            </div>
            {{-- @if($orders->isNotEmpty()) --}}
        </div>
            <div class="card">
                <div class="card-body">
                    <div class="pb-4">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">{{ __('Ledgers') }}</h3>
                            {{-- <button type="button" class="btn btn-outline-success pay-now-btn float-end" 
                              data-bs-toggle="modal" data-bs-target="#payNowModal">
                              Save Now
                            </button> --}}
                        </div>
                    </div>

                    <div class="table-responsive">
                        @include('ledgers.partials.app')
                        <table id='order' class="table table-hover table-montserrat fixed-table">
                            <thead class="thead table-light t-head-clr">
                                <tr>
                                    <th>No #</th>
                                    <th>Date</th>
                                    <th>agent</th>
                                    <th>Customer</th>
                                    <th>Tr Type</th>
                                    <th>Transaction No</th>
                                    <th>Description</th>
                                    <th>Trip Amount</th>
                                    <th>Receivable Amount</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody class="body-font">
                                @php
                                    $row_id = 1;
                                    $row_index = $row_id - 1;
                                    $runningTotal=0;
                                    $debitTotal=0;
                                    $creditTotal=0;
                                @endphp
                                @foreach ($ledgerEntries as $entry)
                                @php
                                    $runningTotal=$runningTotal+$entry->debit-$entry->credit;
                                    $creditTotal=$creditTotal+$entry->credit;
                                    $debitTotal=$debitTotal+$entry->debit;
                                @endphp
                                    <tr id="order_row{{ $row_id }}" data-row-index="{{ $row_index }}">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $entry->tr_date }}</td>
                                        <td>{{ $entry->agent }}</td>
                                        <td>{{ $entry->customer }}</td>
                                        <td>{{ $entry->trtype }}</td>
                                        <td>{{ $entry->tr_no }}</td>
                                        <td>{{ $entry->description }}</td>
                                        @if($entry->trtype=='OPN')
                                        <td>0</td>
                                        <td>0</td>                                
                                        @else
                                        <td>{{ $entry->debit-$entry->credit>=0 ?$entry->debit-$entry->credit : 0 }}</td>
                                        <td>{{ $entry->debit-$entry->credit<0 ? abs($entry->debit - $entry->credit):0 }}</td>
                                        @endif
                                        <td>{{ $runningTotal }}</td>
                                        {{-- <td class="order-rate">{{ $order->rate ?? null }}</td>
                                        <td>
                                            <input type="hidden" name="order_checked[{{ $row_index }}]" value='0'>
                                            <input type="hidden" class="order-id" value="{{ $order->order->id ?? null }}">
                                            <input type="hidden" class="agent-id" value="{{ $order->order->partner_business->id??null }}">
                                            <input type="checkbox" name="order_checked[{{ $row_index }}]" class="order-checkbox" value="{{ $order->rate ?? 0 }}">
                                        </td> --}}
                                    </tr>
                                    @php
                                        $row_id++;
                                        $row_index = $row_id - 1;
                                    @endphp
                                @endforeach
                                <tr id="order_row{{ $row_id }}" data-row-index="{{ $row_index }}">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td>{{ $debitTotal }}</td>
                                    <td>{{ $creditTotal }}</td>
                                    <td>{{ $runningTotal }}</td>
                                    {{-- <td class="order-rate">{{ $order->rate ?? null }}</td>
                                    <td>
                                        <input type="hidden" name="order_checked[{{ $row_index }}]" value='0'>
                                        <input type="hidden" class="order-id" value="{{ $order->order->id ?? null }}">
                                        <input type="hidden" class="agent-id" value="{{ $order->order->partner_business->id??null }}">
                                        <input type="checkbox" name="order_checked[{{ $row_index }}]" class="order-checkbox" value="{{ $order->rate ?? 0 }}">
                                    </td> --}}
                                </tr>
                            </tbody>
                        </table>
                        {{-- {!! $orders->links() !!} --}}
                    </div>
                    {{-- <div class="total-amount">
                        <h4>Total Amount: <span id="total-amount">0</span></h4>
                    </div> --}}
                </div>
            </div>
            {{-- @else --}}
            {{-- <div class="card-body">
                <p> Please use the filter above to search for customers.</p> 
            </div> --}}
            {{-- @endif --}}
        </div>
    
</div>

@include('ledgers.partials.model')

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

        $('.pay-now-btn').click(function() {
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
                }
            });
        });
    });
</script>
@endsection
