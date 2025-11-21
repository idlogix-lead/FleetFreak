<style>
.btn-bulk {
  color: #fff !important;
  background-color: #1A2E97 !important;
  border-color: #1A2E97 !important;
}
.btn-bulk:hover {
  color: #fff;
  background-color:#0b5ed7 !important;
  border-color: #0b5ed7 !important;
}
</style>
<form action="{{ route($link) }}" method="GET">
    <div class="row">
        
        {{-- <div class="col-md-3">
            <div class="form-group">
                <label for="customer">Customer</label>
                <select name="customer" id="customer" class="form-control">
                    <option value="">Search by customer name...</option>
                    @if(request()->input('customer'))
                        <option value="{{ request()->input('customer') }}" selected>{{ request()->input('customer_name') }}</option>
                    @endif
                </select>
                <input type="hidden" name="customer_id" id="customer_id" value="{{ request()->input('customer') }}">
                <input type="hidden" name="customer_name" id="customer_name" value="{{ request()->input('customer_name') }}">
            </div>
        </div> --}}
        <div class="col-md-3">
            <div class="form-group">
                {{-- <label for="agent">Agent</label> --}}
                <select name="agent" id="agent" class="form-control">
                    <option value="">Search by agent name...</option>
                    @if(request()->input('agent'))
                        <option value="{{ request()->input('agent') }}" selected>{{ request()->input('agent_name') }}</option>
                    @endif
                </select>
                <input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
                <input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group mb-3">
                <label for="from_date" class="col-form-label mx-1">From Date:</label>
                <input type="date" id="from_date" class="form-control form-control-sm rounded" placeholder="From Date" name="from_date" value="{{ request()->input('from_date') }}">
            </div>
        </div>

        <!-- To Date Filter -->
        <div class="col-md-3">
            <div class="input-group mb-3">
                <label for="to_date" class="col-form-label mx-1">To Date:</label>
                <input type="date" id="to_date" class="form-control form-control-sm rounded " placeholder="To Date" name="to_date" value="{{ request()->input('to_date') }}">
                <button class="btn btn-bulk btn-sm" type="submit">Search</button>
            </div>
            
        </div>
        {{-- reset button --}}
        <div class="col-md-3">
            <div class="input-group mb-3">
                <button style=" font-size: 14px;" class="btn btn-danger mx-4" id="resetBtn" type="button">Reset</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#resetBtn').click(function() {
            // Reset all form fields
            $('#agent').val(null).trigger('change');
            $('#from_date').val('');
            $('#to_date').val('');
        });
        // Initialize Select2 for customer field
        $('#agent').select2({
            width: '100%',
            placeholder: 'Search by agent name...',
            minimumInputLength: 2,
            ajax: {
                url: '{{ route('agent.search') }}',
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

        // If there's a selected customer, add it to Select2
        // @if(request()->input('customer'))
        //     var selectedCustomerId = '{{ request()->input('customer') }}';
        //     var selectedCustomerName = '{{ request()->input('customer_name') }}';

        //     var option = new Option(selectedCustomerName, selectedCustomerId, true, true);
        //     $('#customer').append(option).trigger('change');
        // @endif

        // // Capture the customer ID on select change
        // $('#customer').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     $('#customer_id').val(data.id);
        //     $('#customer_name').val(data.text);
        // });

        // to display the agent name after selection

        $('#agent').on('select2:select', function (e) {
            var data = e.params.data;
            $('#agent_id').val(data.id);
            $('#agent_name').val(data.text);
        });
    });
</script>