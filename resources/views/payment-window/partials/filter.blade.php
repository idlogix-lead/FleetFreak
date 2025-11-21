<form action="{{ route($link) }}" method="GET">
    <div class="box box-info padding-1">
        <div class="box-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="company_id">Company  </label>
                        <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                        <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                        {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <select name="customer" required class="form-control" id="customer">
                            <option value="">Search by customer name...</option>
                            {{-- @if(request()->input('customer'))
                                <option value="{{ request()->input('customer') }}" selected>{{ request()->input('customer_name') }}</option>
                            @endif --}}
                            @if(isset($payment_header))
                            <option value="{{ $payment_header->customer_id }}" selected>{{ $payment_header->partner_customer->name}} </option>
                            @else
                            <option value="{{ request()->input('customer') }}" selected>{{ request()->input('customer_name') }}</option>
                            @endif
                        </select>
                        <input type="hidden" name="customer_id" id="customer_id" value="{{ request()->input('customer') }}">
                        <input type="hidden" name="customer_name" id="customer_name" value="{{ request()->input('customer_name') }}">
                    </div>
                </div>
             <!-- New Agent Select Field -->
             {{-- <div class="col-md-4">
                <div class="form-group">
                    <label for="agent">Agent</label>
                    <select name="agent" class="form-control" id="agent">
                        <option value="">Search by agent name...</option>
                        @php
                        $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                    @endphp
                    @foreach($businessPartners as $business_partner)
                        <option value="{{ $business_partner->id }}"
                            @if (isset($payment_header))
                                {{$payment_header->agent_id == $business_partner->id ? 'selected': ''}}
                            @else
                            {{ request()->input('agent') == $business_partner->id ? 'selected' : '' }}
                            @endif
                        >{{ Str::title($business_partner->company_name) }}</option>
                    @endforeach
                    </select>
                    <input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
                    <input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}">
                </div>
            </div> --}}

            <div class="col-md-4">
                <div class="form-group">
                    <label for="order_no">Date</label>
                    @if (isset($payment_header))
                    <input type="date" name="date" required class="form-control" id="date" value="{{$payment_header->date}}" required>
                    @else
                    <input type="date" name="date" required class="form-control" id="date" value="{{request()->input('date')?? now()->format('Y-m-d')  }}" required>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" name="description" class="form-control" id="description" value="{{request()->input('description') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="total_amount">Total Amount</label>
                    @if (isset($payment_header))
                    <input type="text" readonly name="total_amount" class="form-control" id="total_amount" value="{{$payment_header->total_amount}}">
                    @else
                    <input type="text" readonly name="total_amount" class="form-control" id="total_amount" value="{{request()->input('total_amount') }}">
                    @endif
                </div>
            </div>
            <div>

                <button class="btn btn-secondary float-end my-3" id="searchBtn" type="submit">Load Rides</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#customer').select2({
            width: '100%',
            placeholder: 'Search by customer name...',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('customer.search') }}',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.name,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        // Initialize agent select2 field
        // $('#agent').select2({
        //     width: '100%',
        //     placeholder: 'Search by agent name...',
        //     minimumInputLength: 2,
        //     ajax: {
        //         url: '{{ route('agent.search') }}',
        //         dataType: 'json',
        //         delay: 250,
        //         processResults: function (data) {
        //             return {
        //                 results: $.map(data, function (item) {
        //                     return {
        //                         text: item.name,
        //                         id: item.id
        //                     }
        //                 })
        //             };
        //         },
        //         cache: true
        //     }
        // });


        // If there's a selected customer, add it to Select2
        @if(request()->input('customer'))
            var selectedCustomerId = '{{ request()->input('customer') }}';
            var selectedCustomerName = '{{ request()->input('customer_name') }}';

            var option = new Option(selectedCustomerName, selectedCustomerId, true, true);
            $('#customer').append(option).trigger('change');
        @endif
        // @if(request()->input('agent'))
        //     var selectedAgentId = '{{ request()->input('agent') }}';
        //     var selectedAgentName = '{{ request()->input('agent_name') }}';

        //     var option = new Option(selectedAgentName, selectedAgentId, true, true);
        //     $('#agent').append(option).trigger('change');
        // @endif

        // Capture the customer ID on select change
        $('#customer').on('select2:select', function (e) {
            var data = e.params.data;
            $('#customer_id').val(data.id);
            $('#customer_name').val(data.text);
        });
        // $('#agent').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     $('#agent_id').val(data.id);
        //     $('#agent_name').val(data.text);

        // });
        $('#searchBtn').click(function(event) {
            var customer = $('#customer').val();
            var agent = $('#agent').val();
            // var date = $('#date').val();
            if (customer || agent) {
                $('#searchForm').submit(); // Submit the form if date is filled and either customer or agent is filled
            } else {
            msgboxbox.show("Date is required and either customer or agent must be selected.",'error', null);

                event.preventDefault();
            }
            // if (date && (customer || agent)) {
            //     $('#searchForm').submit(); // Submit the form if date is filled and either customer or agent is filled
            // } else {
            // msgboxbox.show("Date is required and either customer or agent must be selected.",'error', null);

            //     event.preventDefault();
            // }
        });
        // $('#searchBtn').click(function() {
        //     var customer = $('#customer').val();
        //     var date = $('#date').val();

        //     if (customer && date) {
        //         $('#searchForm').submit(); // Submit the form if both fields are filled
        //     } else {
        //         msgboxbox.show("Both customer and date are required for search.",'error', null);

        //         event.preventDefault();
        //     }
        // });
    });
</script>
