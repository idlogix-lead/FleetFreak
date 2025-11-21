<style>
    .btn-ledger {
  color: #fff !important;
  background-color: #1A2E97 !important;
  border-color: #1A2E97 !important;
}
.btn-ledger:hover {
  color: #fff;
  background-color:#0b5ed7 !important;
  border-color: #0b5ed7 !important;
}
</style>

<form action="{{ route($link) }}" method="GET" id="searchForm">
    <div class="box box-info padding-1">
        <div class="box-body">
            <div class="row">
                {{-- <div class="col-md-3">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search by customer name..." name="customer" value="{{ request()->input('customer') }}">
                        <button class="btn btn-secondary" type="submit">Search</button>
                    </div>
                </div> --}}
                @if(auth()->user()->actor_id==2)
                <div class="col-md-3">
                    <div class="input-group mb-3">
                      
                        <select name="agent" class="form-control" id="agent">
                            <option value="">Search by agent name...</option>
                            @if(request()->input('agent'))
                                <option value="{{ request()->input('agent') }}" selected>{{ request()->input('agent_name') }}</option>
                            @endif
                        </select>
                        <input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
                        <input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}">
                    </div>
                </div>
                @endif
                <div class="col-md-3">
                    <div class="input-group ">
                        <label for="to_date" class="col-form-label mx-1">From Date:</label>
                        <input type="date" class="form-control" id="from_date" placeholder="From Date" name="from_date" value="{{ request()->input('from_date') }}">
                    </div>
                </div>
        
                <!-- To Date Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label for="to_date" class="col-form-label mx-1">To Date:</label>
                        <input type="date" class="form-control" id="to_date" placeholder="To Date" name="to_date" value="{{ request()->input('to_date') }}">
                        {{-- <button class="btn btn-secondary" type="submit">Search</button> --}}
                    </div>
                </div>
                <div>
                    {{-- <button class="btn btn-secondary float-end my-3" id="searchBtn" type="submit">View Ledgers</button> --}}
                    <button class="btn btn-ledger float-end my-3" id="searchBtn" type="submit">View Ledgers</button>
             
                </div>
            </div>
        </div>
    </div>
</form>

<script>
   $(document).ready(function() {
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

        // If there's a selected agent, add it to Select2
        @if(request()->input('agent'))
            var selectedAgentId = '{{ request()->input('agent') }}';
            var selectedAgentName = '{{ request()->input('agent_name') }}';

            var option = new Option(selectedAgentName, selectedAgentId, true, true);
            $('#agent').append(option).trigger('change');
        @endif

        // Capture the agent ID on select change
        $('#agent').on('select2:select', function (e) {
            var data = e.params.data;
            $('#agent_id').val(data.id);
            $('#agent_name').val(data.text);
        });

        // Prevent form submission if both fields are not filled
        $('#searchForm').on('submit', function(event) {
            // var agent = $('#agent').val();
            var fromDate = $('#from_date').val();
            var toDate = $('#to_date').val();

            if ( !fromDate || !toDate) {
                event.preventDefault();
                msgboxbox.show("both dates are required for search.",'error', null);

                
            }
        });
    });
    
</script>
