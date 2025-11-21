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
                    <label for="agent_id">Business Partner</label>
                    <select name="agent_id" class="form-control select2" id="agent_id" required>
                        <option value="">Search by agent name...</option>
                        @php
                        $businessPartners = App\Models\Partner::BusinessPartnerDropdownAgent();
                    @endphp
                    @foreach($businessPartners as $business_partner)
                    <option value="{{ $business_partner->id }}"
                        {{ $payment_headers->agent_id == $business_partner->id ? 'selected' : '' }}>
                        {{ Str::title($business_partner->name) }}</option>
                    @endforeach
                    </select>

                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$payment_headers->description??'')}}">
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control {{($errors->has('date') ? ' is-invalid' : '')}}" id="date" value="{{old('date', $payment_headers->date??'')}}" autofocus required>
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" required name="amount" class="form-control {{($errors->has('amount') ? ' is-invalid' : '')}}" id="amount" value="{{old('amount',$payment_headers->total_amount??'')}}">
                    {!! $errors->first('amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <input type="hidden" name="status" id="req_status" value=''>

        </div>
    </div>

    <div class="box-footer mt20">
        @php
        $model=[
            'notify_btn' => "Confirm",
            'function' => "Save",
            'body' => 'Please Confirm do you realy want to Confirmed?',
            'btn-color' => 'danger',
            'float' => "end mt-2",
            'id' => "confirmed"
            ];
        @endphp
        @include('partials.modal', ['data'=>$model])
    </div>
    <div class="box-footer mt20">
        @php
        $model=[
            'notify_btn' => "Draft",
            'function' => "Save",
            'body' => 'Please Confirm do you realy want to Save?',
            'btn-color' => 'primary',
            'float' => "end mt-2",
            'id' => "draft"
            ];
        @endphp
        @include('partials.modal', ['data'=>$model])
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#agent_id').select2({
            width: '100%',
            placeholder: 'Search by partner name...',
        })

        $('#btn_confirmed').click(function(e) {
            $('#req_status').val('paid');
            console.log($('#req_status').val());
            // msgboxbox.show('your request has been submited','success', null);
            // validate_all(e);
            // total_expense();
        });
        $('#btn_draft').click(function(e) {

            $('#req_status').val('draft');
            console.log($('#req_status').val());

        });
        $('#amount').on('input', function() {
                var inputValue = $(this).val().trim();
                // Remove non-numeric characters
                var numericValue = inputValue.replace(/\D/g, '');
                // Update the input field value
                $(this).val(numericValue);
            });
    });
</script>
