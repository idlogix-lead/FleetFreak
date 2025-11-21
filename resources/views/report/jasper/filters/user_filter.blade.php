<div class="card no-print" >
    <h6 class="card-title pt-4 pl-4">
        @include('report.jasper.partials.export')
        <span data-toggle="collapse" href="#{{$filter}}" aria-controls="{{$filter}}" role="button" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter text-primary"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            User Filter
        </span>
    </h6>

    <div id="{{$filter}}" class="collapse"  >
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    @include('report.jasper.partials.daterange')
                </div>
                {{-- <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Request No</label>
                        <input type="text" name="request_no" id="request_no" placeholder="REQ-0000" autofocus class="form-control">
                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Payment Status</label>
                        <select name="payment_status" id="payment_status" class="form-control">
                            <option value="">--Select--</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Request Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">--Select--</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Invalid">Invalid</option>
                            <option value="Edit Required">Edit Required (Refered back)</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Approved">Approved</option>
                        </select>
                    </div>
                </div>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                    @include('report.jasper.partials.functions')
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var filter_fields = [
        {
            'field':'filter_date_range',
            'type':'input'
        },
        {
            'field':'request_no',
            'type':'input'
        },
        {
            'field':'payment_status',
            'type':'select'
        },
        {
            'field':'status',
            'type':'select'
        }
    ]

    // $(document).ready(function(){
    //     $('#{{$filter}}').addClass('show');
    // });
</script>
