<style>
    .btn-search {
  color: #fff !important;
  background-color: #1A2E97 !important;
  border-color: #1A2E97 !important;
}
.btn-search:hover {
  color: #fff;
  background-color:#0b5ed7 !important;
  border-color: #0b5ed7 !important;
}
.clear-date {
    font-size: 24px; /* Increase size of the cross icon */
    line-height: 24px; /* Adjust vertical alignment */
    color: #d04d4d;
    z-index: 2;
    right: 1px;
    top: 50%;
    transform: translateY(-50%);
    position: absolute;
    cursor: pointer;
    /* width: 24px; /* Optional: Set a fixed width */
    /* height: 24px; Optional: Set a fixed height */
    display: flex;
    justify-content: center; /* Center the icon horizontally */
    align-items: center; /* Center the icon vertically */
}
.clear-date:hover {
    color: #333; /* Change color on hover for better UX */
}


</style>
<form action="{{ route($link , $ordertype) }}" method="GET">
    <div class="row">

        {{-- <div class="col-md-3">
            <div class="input-group mb-3">
                <label for="date" class="col-form-label mx-1">From Date:</label>
                <input type="date" class="form-control" placeholder="From Date" name="date" value="{{$fromDate ?? request()->input('date') }}">
            </div>
        </div> --}}

        <!-- To Date Filter -->
        {{-- <div class="col-md-3">
            <div class="input-group mb-3">
                <label for="to_date" class="col-form-label mx-1">To Date:</label>
                <input type="date" class="form-control" placeholder="To Date" name="to_date" value="{{$toDate ?? request()->input('to_date') }}">

            </div>
        </div> --}}

        <div class="col-md-3">
            <div class="input-group mb-3 position-relative">
                <label for="date" class="col-form-label mx-1">From Date:</label>
                <input type="date" class="form-control" placeholder="From Date" id="fromDate" name="date" value="{{$fromDate ?? request()->input('date') }}">
                <span class="clear-date position-absolute"data-target="#fromDate">×</span>
            </div>
        </div>

        <div class="col-md-3">
            <div class="input-group mb-3 position-relative">
                <label for="to_date" class="col-form-label mx-1">To Date:</label>
                <input type="date" class="form-control" placeholder="To Date" id="toDate" name="to_date" value="{{$toDate ?? request()->input('to_date') }}">
                <span class="clear-date position-absolute" data-target="#toDate">×</span>
            </div>
        </div>

        {{-- for status dropdown --}}
        <div class="col-md-3">
            <div class="input-group mb-3">
                <label for="status" class="col-form-label mx-1">Status:</label>
                <select class="form-control" name="status" id="status">
                    <option value="">Select Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ $ordertype == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                <button class="btn btn-search" type="submit">Search</button>
            </div>

        </div>

        <div class="col-md-3">
            <div class="input-group mb-3">
                <a href="{{ route('total-rides') }}">
                <button type="button" class="btn btn-secondary" id="resetDates">Reset</button>
                </a>
            </div>
        </div>

    </div>
</form>
<script>
    $(document).ready(function() {

        $('input[type="date"]').on('input', function() {
            const clearIcon = $(`.clear-date[data-target="#${this.id}"]`);
            if ($(this).val()) {
                clearIcon.show(); // Show the clear icon
            } else {
                clearIcon.hide(); // Hide the clear icon
            }
        });

        $('.clear-date').on('click', function() {
            const target = $($(this).data('target'));
            target.val(''); // Clear the input value
            $(this).hide(); // Hide the clear icon
        });


        // Initialize Select2 for customer field
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

        // $('#agent').on('select2:select', function (e) {
        //     var data = e.params.data;
        //     $('#agent_id').val(data.id);
        //     $('#agent_name').val(data.text);
        // });
    });

</script>
