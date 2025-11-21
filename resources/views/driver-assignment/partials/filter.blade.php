<form action="{{ route($link) }}" method="GET">
    @if(auth()->user()->actor_id==2)
    <div class="row">
        <div class="col-md-3">
            <div class="input-group mb-3">
                <input type="text" id="agent" class="form-control" placeholder="Search by agent name..." name="query" value="{{ request()->input('query') }}">
                {{-- <button class="btn btn-secondary" type="submit">Search</button> --}}
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group mb-3">
                <input type="text" id="customer" class="form-control" placeholder="Search by customer name..." name="customer" value="{{ request()->input('customer') }}">
                {{-- <button class="btn btn-secondary" type="submit">Search</button> --}}
            </div>
        </div>
    @endif
        <div class="col-md-3">
            <div class="input-group mb-3">
                <input type="date" id = "date" class="form-control" name="date" value="{{ request()->input('date') }}">
                <button class="btn btn-secondary" type="submit">Search</button>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group mb-3">
                <button style="" class="btn btn-danger mx-4" id="resetBtn" type="button">Reset</button>
            </div>
        </div>
    </div>
</form>
<script>
     $(document).ready(function() {
        $('#resetBtn').click(function() {
            // Reset all form fields
            $('#agent').val('');
            $('#customer').val('');
            $('#date').val('');
        });
    });
</script>
