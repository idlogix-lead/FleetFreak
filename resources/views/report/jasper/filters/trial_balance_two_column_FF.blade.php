@php
    $client_id=auth()->user()->client_id;
    $company_id=auth()->user()->active_company();
@endphp
<div class="card no-print" >
    {{-- <h6 class="card-title pt-4 pl-4" > --}}
        {{-- @include('report.jasper.partials.export') --}}
        {{-- <span  data-toggle="collapse" href="#{{$filter}}" aria-controls="{{$filter}}" role="button" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter text-primary"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            Trial Balance
        </span> --}}
        <span data-bs-toggle="collapse" href="#{{$filter}}" aria-controls="{{$filter}}" role="button" aria-expanded="false">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#640d5f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter text-primary"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
             Filter
        </span>
    {{-- </h6> --}}
    <div id="{{$filter}}" class="collapse">
        <div class="card-body pt-0">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-xl-3">
                    @include('report.jasper.partials.daterange',['timePicker' => false])
                </div>
                {{-- <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Cient ID</label>
                        <input type="text" name="client_id" id="client_id" value="{{ $client_id }}"  autofocus class="form-control" readonly>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-xl-3">
                    <div class="form-group">
                        <label for="">Company ID</label>
                        <input type="text" name="company_id"  id="company_id" value="{{ $company_id }}" autofocus class="form-control" readonly>
                    </div>
                </div> --}}

                 <div class="col-md-6 mt-3">
                    @include('report.jasper.partials.functions')
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-12 float-right">
                    @include('report.jasper.partials.functions')
                </div>
            </div> --}}
        </div>
    </div>
</div>
<script>
    var filter_fields = [
        {
            'field':'filter_date_range',
            'type':'input'
        }
        // {
        //     'field':'client_id',
        //     'type':'input'
        // },
        // {
        //     'field':'company_id',
        //     'type':'input'
        // },
    ]
</script>

