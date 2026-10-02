@php
    $disabled = '';
    if ($activity->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp

</style>
<div class="modal fade" id="documentActionModal" tabindex="-1" aria-labelledby="documentActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header text-white">
                <h5 class="modal-title fw-bold" id="documentActionModalLabel">
                    <i class="bi bi-file-earmark-text"></i> Select Document Action
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3 text-muted">Are you sure you want to complete this document?</p>
                
                <!-- Buttons in a single row -->
                <div class="d-flex justify-content-center gap-2">
                    {{-- <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center" id="draftBtn">
                        <i class="bi bi-pencil-square me-1"></i> Draft
                    </button> --}}
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center" id="completeBtn">
                        <i class="bi bi-check-circle me-1"></i> Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- -------------------- --}}
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="company_id">Company  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" readonly name="company_id" class="form-control small-input form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="document_no">Document No</label>
                <input readonly type="text" name="document_no" class="form-control small-input form-control-sm {{($errors->has('document_no') ? ' is-invalid' : '')}}" id="document_no" value="{{old('document_no',$activity->document_no)}}">
                {!! $errors->first('document_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="document_status">Document Status</label>
                <input type="hidden" name="document_status" id="document_status_hidden"
                    value="{{ $activity->document_status ? $activity->document_status : 'draft' }}">
                <input type="text" {{ $disabled }} autofocus required readonly placeholder="Document Status"
                    class="form-control small-input form-control-sm {{ $errors->has('document_status') ? ' is-invalid' : '' }}"
                    id="document_status"
                    value="{{ $activity->document_status ? ucfirst($activity->document_status) : 'Draft' }}">
                {!! $errors->first('document_status', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_action">Document Action</label>
                <button type="button" {{$disabled}} class="btn btn-primary btn-sm w-100" id="document_action_btn">
                    {{ $activity->document_action ?? 'Action' }}
                </button>
                <input type="hidden" name="document_action" id="document_action_input" value="{{ $activity->document_action }}">
            </div>
        </div>       
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="date_ordered">Date Ordered</label>
                <input {{ $disabled }} required type="text" name="date_ordered" class="form-control flatpickr small-input form-control-sm {{($errors->has('date_ordered') ? ' is-invalid' : '')}}" id="date_ordered" value="{{old('date_ordered',$activity->date)}}">
                {!! $errors->first('date_ordered', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_type">Document Type</label>
                <input readonly type="text" name="document_type" class="form-control small-input form-control-sm {{($errors->has('document_type') ? ' is-invalid' : '')}}" id="document_type" value="Purchase Invoice">
                {!! $errors->first('document_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="date_invoiced">Date Invoiced</label>
                <input {{ $disabled }} type="date" placeholder="date_invoiced" name="date_invoiced" class="form-control small-input form-control-sm {{($errors->has('date_invoiced') ? ' is-invalid' : '')}}" id="date_invoiced" value="{{old('date_invoiced',$activity->date_invoiced)}}" autofocus>
                {!! $errors->first('date_invoiced', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="date_invoiced">Date Invoiced</label>
                <input {{ $disabled }} type="text" placeholder="Select Date" 
                    name="date_invoiced" 
                    class="form-control small-input form-control-sm flatpickr {{ $errors->has('date_invoiced') ? ' is-invalid' : '' }}" 
                    id="date_invoiced" 
                    value="{{ old('date_invoiced', $activity->date_invoiced ?? now()->format('Y-m-d')) }}" 
                    autocomplete="off">
                {!! $errors->first('date_invoiced', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="account_date">Account Date </label>
                <input {{ $disabled }} type="text" placeholder="select date" name="account_date" class="form-control flatpickr small-input form-control-sm {{($errors->has('account_date') ? ' is-invalid' : '')}}" id="account_date" value="{{old('account_date',$activity->account_date)}}" autofocus>
                {!! $errors->first('account_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="business_partner_id">Business Partner</label>
                <select {{ $disabled }} name="business_partner_id" autofocus required id="business_partner_id"
                    class="form-control red-border-select2 {{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Partner::vendorDropdown() as $business_partner)
                        <option value="{{ $business_partner->id }}"
                            {{ old('business_partner_id', $activity->business_partner_id) == $business_partner->id ? 'selected' : '' }}>
                            {{ Str::title($business_partner->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="material_inout_id">Material Receipt</label>
                <select {{ $disabled }} name="material_inout_id" id="material_inout_id" autofocus required
                    class="form-control red-border-select2 {{ $errors->has('material_inout_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                </select>
                {!! $errors->first('material_inout_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <input type="hidden" name="order_id", value="" id="order_id">
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="price_list_id">Price List</label>
                <select {{ $disabled }} name="price_list_id" autofocus required id="price_list_id"
                    class="form-control red-border-select2 {{ $errors->has('price_list_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\PriceList::pricelist() as $pricelist)
                        <option value="{{ $pricelist->id }}"
                            {{ old('price_list_id', $activity->price_list_id) == $pricelist->id ? 'selected' : '' }}>
                            {{ Str::title($pricelist->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="price_list_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('price_list_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="payment_term">Payment_term</label>
                <input {{ $disabled }} type="text" placeholder="payment_term" name="payment_term" class="form-control small-input form-control-sm {{($errors->has('payment_term') ? ' is-invalid' : '')}}" id="payment_term" value="{{old('payment_term',$activity->payment_term)}}" autofocus>
                {!! $errors->first('payment_term', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="payment_rule">Payment_rule</label>
                <input {{ $disabled }} type="text" placeholder="payment_rule" name="payment_rule" class="form-control small-input form-control-sm {{($errors->has('payment_rule') ? ' is-invalid' : '')}}" id="payment_term" value="{{old('payment_rule',$activity->payment_rule)}}" autofocus>
                {!! $errors->first('payment_rule', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="discount_printed">Discount Printed</label>
                <input {{ $disabled }} type="text"  name="discount_printed" class="form-control small-input form-control-sm {{($errors->has('discount_printed') ? ' is-invalid' : '')}}" id="discount_printed" value="{{old('discount_printed',$activity->discount_printed)}}" autofocus>
                {!! $errors->first('discount_printed', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="c_cols col-sm-6 col-md-3 col-lg-3">
            <div class="form-group top-card-form-group">
                <label for="total_amount">Total Amount </label>
                <div class="input-group input-group-sm"> <span
                        class="input-group-text  pending-rides-icon"><svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 24 24">
                            <path
                                d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z">
                            </path>
                            <path
                                d="M12 11c-2 0-2-.63-2-1s.7-1 2-1 1.39.64 1.4 1h2A3 3 0 0 0 13 7.12V6h-2v1.09C9 7.42 8 8.71 8 10c0 1.12.52 3 4 3 2 0 2 .68 2 1s-.62 1-2 1c-1.84 0-2-.86-2-1H8c0 .92.66 2.55 3 2.92V18h2v-1.08c2-.34 3-1.63 3-2.92 0-1.12-.52-3-4-3z">
                            </path>
                        </svg></span>
                    <input {{ $disabled }} type="text" readonly value="{{ $activity->total_amount }}"
                        name="total_amount"
                        class="form-control {{ $errors->has('total_amount') ? ' is-invalid' : '' }}"
                        id="total_amount" autofocus>
                    {!! $errors->first('total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        <div class="c_cols col-sm-6 col-md-3 col-lg-3">
            <div class="form-group top-card-form-group">
                <label for="grand_total_amount">Grand Total Amount </label>
                <div class="input-group input-group-sm"> <span
                        class="input-group-text  pending-rides-icon"><svg
                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z">
                            </path>
                            <path
                                d="M12 11c-2 0-2-.63-2-1s.7-1 2-1 1.39.64 1.4 1h2A3 3 0 0 0 13 7.12V6h-2v1.09C9 7.42 8 8.71 8 10c0 1.12.52 3 4 3 2 0 2 .68 2 1s-.62 1-2 1c-1.84 0-2-.86-2-1H8c0 .92.66 2.55 3 2.92V18h2v-1.08c2-.34 3-1.63 3-2.92 0-1.12-.52-3-4-3z">
                            </path>
                        </svg></span>
                    <input {{ $disabled }} type="text" readonly value="{{ $activity->grand_total_amount }}"
                        name="grand_total_amount"
                        class="form-control {{ $errors->has('grand_total_amount') ? ' is-invalid' : '' }}"
                        id="grand_total_amount" autofocus>
                    {!! $errors->first('grand_total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-9 col-lg-9">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea {{ $disabled }} name="description" 
                    class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" 
                    id="description" rows="3">{{ old('description', $activity->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <!-- Checkboxes taking 3 columns and stacked vertically -->
        <div class="col-md-3 d-flex flex-column justify-content-start my-4">
            {{-- <div class="form-group form-check mb-3">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_pay_schedule_valid" id="is_pay_schedule_valid_hidden">
                
                <!-- Toggle Switch -->
                <label class="switch">
                    <input type="checkbox" value="1" name="is_pay_schedule_valid" id="is_pay_schedule_valid"
                        {{ (isset($activity) && $activity->is_pay_schedule_valid === 0) ? '' : 'checked' }}>
                    <span class="slider round"></span>
                </label>
                
                <!-- Label for the toggle -->
                <label class="form-check-label" for="is_pay_schedule_valid">Pay Schedule Valid</label>
            </div> --}}

            {{-- <div class="col-md-4 my-4"> --}}
                <div class="form-group form-check">
                    <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                    <input type="hidden" value="0" name="is_active" id="is_active_hidden">
                    
                    <!-- Toggle Switch -->
                    <label class="switch">
                        <input type="checkbox" value="1" name="is_active" id="is_active"
                            {{ (isset($activity) && $activity->is_active === 0) ? '' : 'checked' }}>
                        <span class="slider round"></span>
                    </label>
                    
                    <!-- Label for the toggle -->
                    <label class="form-check-label" for="is_active">Is Active</label>
                </div>
            {{-- </div> --}}
        </div>
        
       
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="material_inout_id">Purchase Order</label>
                <select {{ $disabled }} name="material_inout_id" id="material_inout_id" autofocus required
                    class="form-control red-border-select2 {{ $errors->has('material_inout_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                </select>
                {!! $errors->first('material_inout_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
       
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="rma">RMA</label>
                <input {{ $disabled }} type="text" name="rma" class="form-control {{($errors->has('rma') ? ' is-invalid' : '')}}" id="rma" value="{{old('rma',$activity->rma)}}">
                {!! $errors->first('rma', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="accounting_date">Accounting Date</label>
                <input {{ $disabled }} required type="date" name="accounting_date" class="form-control {{($errors->has('accounting_date') ? ' is-invalid' : '')}}" id="accounting_date" value="{{old('accounting_date',$activity->accounting_date)}}">
                {!! $errors->first('accounting_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="invoice_partner_id">Invoice Partner</label>
                <select {{ $disabled }} name="invoice_partner_id" autofocus required id="invoice_partner_id"
                    class="form-control {{ $errors->has('invoice_partner_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Partner::BusinessPartnerDropdownSimple() as $business_partner)
                        <option value="{{ $business_partner->id }}"
                            {{ $activity->invoice_partner_id== $business_partner->id ? 'selected' : '' }}>
                            {{ Str::title($business_partner->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('invoice_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="gate_inout">Gate In/Out</label>
                <input {{ $disabled }} type="text" placeholder="gate_inout" name="gate_inout" class="form-control {{($errors->has('gate_inout') ? ' is-invalid' : '')}}" id="gate_inout" value="{{old('gate_inout',$activity->gate_inout)}}">
                {!! $errors->first('gate_inout', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="create_lines_from">Create Lines From</label>
                <input {{ $disabled }} type="text" name="create_lines_from" class="form-control {{($errors->has('create_lines_from') ? ' is-invalid' : '')}}" id="create_lines_from" value="{{$activity->create_lines_from}}">
                {!! $errors->first('create_lines_from', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="c_l_from_gatepass">Create Lines From Gatepass</label>
                <input {{ $disabled }} type="text" name="c_l_from_gatepass" class="form-control {{($errors->has('c_l_from_gatepass') ? ' is-invalid' : '')}}" id="c_l_from_gatepass" value="{{$activity->c_l_from_gatepass}}">
                {!! $errors->first('c_l_from_gatepass', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        

        @if (!$disabled)
            {{-- <div class="col-sm-6 col-md-3 col-lg-3 mt-4"> --}}
                <div class="form-group">
                    <button id="fetchbtn" type="button" class="btn btn-sm btn-primary float-end my-2">
                        {{-- <i class="fas fa-plus"></i> <!-- Small icon --> --}}
                        Fetch From Receipt
                    </button>
                </div>
            {{-- </div> --}}
        @endif
       
        
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$activity->company_id}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        {{-- <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div> --}}
        {{-- @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\Activity::where('company_id',auth()->user()->active_company())->where('is_active', 1)->exists();
        @endphp

        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ old('is_active', $activity->is_active) ? 'checked' : '' }}
                    @if($isActiveDisabled && $activity->is_active== 0) disabled @endif>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div> --}}

        </div>
    </div>
    {{-- <div class="box-footer mt20">
        @php
        $model=[
            'notify_btn' => "Save",
            'function' => "Save",
            'body' => 'Please Confirm do you realy want to Save?',
            'btn-color' => 'primary',
            'float' => "end mt-2",
            'id' => "save"
            ];
        @endphp
        @include('partials.modal', ['data'=>$model])
    </div> --}}
</div>
<br>
{{-- <div class="container"> --}}
    
    <div class="">
        {{-- <div class="col-auto"> --}}
            <!-- Add Row Button -->
            @if (!$disabled)
            <button type="button" class="btn btn-sm btn-outline-primary float-end" onclick="openModal()" data-toggle="tooltip" data-placement="left" title="Add Row">
                <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </button>
            @endif
        {{-- </div> --}}
        <h4>Invoice Line</h4>

    </div>
    <div class="table-responsive">
        <table id="material_lines_table" class="table new-table table-sm small">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Product <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{-- <th>Locator <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Description <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Unit Measure <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{-- <th>Quantity Invoiced <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Rate <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{-- <th>Unit Rate <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>List Rate <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Tax <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Tax Amount <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Line Total <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Total Line Amount <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    
                   
                    {{-- <th>Adult</th> --}}
                    {{-- <th>Child</th> --}}
                            {{-- <th>Function</th>
                    <th>Return</th> --}}
                    {{-- <th>Bags</th> --}}
                    <th>Actions <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{-- <th>Pickup Time <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Is Ac <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th id="isReturnHeader">Is Return <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Action <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                </tr>
            </thead>
            <tbody id="tableBody">

            </tbody>
        </table>
    </div>
</div>
<div class="container">
    {{-- @if (!$disabled)
        @php
            $model = [
                'notify_btn' => 'Draft',
                'function' => 'Save',
                'body' => 'Please Confirm do you realy want to Draft?',
                'btn-color' => 'primary',
                'float' => 'end mt-2',
                'id' => 'draft',
            ];

            $model2 = [
                'notify_btn' => 'Save as Completed',
                'function' => 'Save',
                'body' => 'Please Confirm do you realy want to Save?',
                'btn-color' => 'success',
                'float' => 'end mt-2',
                'id' => 'complete',
            ];
        @endphp
        @include('partials.new-modal-btn', ['data' => $model])
        @include('partials.new-modal-btn', ['data' => $model2])
    @endif --}}
</div>
<script>
    var oldValues = @json(old('rows', []));
</script>
<script>
    var row_index = -1;
    let rows_id = 1;
    let seqNo = 1;
    async function add_invoiceLine_row(row={}){
        row_index = row_index+1;
        row_id = rows_id;
        // Use the current value of seqNo or the one provided in the row data
        let seqNoValue = row.seq_no ?? seqNo;

        let row_html = `
            <tr id='row_${row_id}'>

                <td>

                    <input type="hidden"  id="order_detail_line_id_${row_id}" value="${row.order_detail_line_id??""}" name="rows[${row_index}][order_detail_line_id]">
                    <input type="hidden"  id="material_inout_line_id_${row_id}" value="${row.material_inout_line_id??""}" name="rows[${row_index}][material_inout_line_id]">
                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
                    <input  type="text" readonly required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control form-control-sm"></td>
                <td style="min-width:200px;">
                    <select autofocus required {{ $disabled }} style="width:100%;" name="rows[${row_index}][product_id]" id="product_id_${row_index}" class="form-control form-control-sm" onchange="sendUom(this)">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Product::dropdown() as $product)
                            <option value="{{ $product->id }}" ${'{{ $product->id }}' == row.product_id ? 'selected' : ''} data-unit="{{ $product->unit_measure_id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <!--
                <td><input {{ $disabled }}  type="text" value="${row.description??""}" name="rows[${row_index}][description]" id="description_${row_index}" class="form-control form-control-sm"></td>-->
                <td><input {{ $disabled }} readonly  type="number" autofocus required value="${row.quantity??""}" name="rows[${row_index}][quantity]" id="quantity_${row_index}" class="form-control form-control-sm" data-materialdetailid ="${row.material_inout_line_id ?? ""}"></td>
                <td style="min-width:200px;">
                    <select style="width:100%;" autofocus required {{$disabled}} name="rows[${row_index}][unit]" id="unit_${row_index}" class="form-control form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\UnitMeasure::unit_type() as $unit)
                            <option value="{{ $unit->id }}" ${oldValues?.rows?.[row_index]?.unit == {{ $unit->id }} ? 'selected' : row?.unit == {{ $unit->id }} ? 'selected' : ''}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <!--<td><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.quantity_invoiced ?? row?.quantity_invoiced ?? ''}" name="rows[${row_index}][quantity_invoiced]" id="quantity_invoiced_${row_index}" class="form-control form-control-sm"></td>-->
                <td style="min-width:100px;"><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.rate ?? row?.rate ?? ''}" name="rows[${row_index}][rate]" id="rate_${row_index}" class="form-control form-control-sm rate-input" autofocus required></td>
                <!--<td><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.unit_rate ?? row?.unit_rate ?? ''}" name="rows[${row_index}][unit_rate]" id="unit_rate_${row_index}" class="form-control form-control-sm"></td>
                <td><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.list_rate ?? row?.list_rate ?? ''}" name="rows[${row_index}][list_rate]" id="list_rate_${row_index}" class="form-control form-control-sm"></td>-->
                <!--<td><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.tax ?? row?.tax ?? ''}" name="rows[${row_index}][tax]" id="tax_${row_index}" class="form-control form-control-sm"></td>-->
                <td style="min-width:100px;">
                    <select style="width:100%;" {{$disabled}} name="rows[${row_index}][tax]" id="tax_${row_index}" class="form-control form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Tax::Taxes() as $tax)
                            <option value="{{ $tax->id }}" data-rate="{{ $tax->rate }}" ${oldValues?.rows?.[row_index]?.tax == {{ $tax->id }} ? 'selected' : row?.tax == {{ $tax->id }} ? 'selected' : ''}>{{ $tax->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" {{ $disabled }}  value="${oldValues?.rows?.[row_index]?.tax_amount ?? row?.tax_amount ?? ''}" name="rows[${row_index}][tax_amount]" id="tax_amount_${row_index}" class="form-control form-control-sm"></td>
                <td><input readonly type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.line_amount ?? row?.line_amount ?? ''}" name="rows[${row_index}][line_amount]" id="line_amount_${row_index}" class="form-control form-control-sm"></td>
                <td><input readonly type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.total_line_amount ?? row?.total_line_amount ?? ''}" name="rows[${row_index}][total_line_amount]" id="total_line_amount_${row_index}" class="form-control form-control-sm"></td>


                <td>
                    <!--<button type="button" class="btn btn-outline-primary btn-sm" onclick="openRowPopup('${row_id}')" data-toggle="tooltip" data-placement="left" title="Edit Row">
                        <i class="fas fa-edit"></i> <!-- Edit icon
                    </button>-->
                    <div class="text-danger rm-row mt-1" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea356f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                    </div>
                </td>
            </tr>
        `;
        $('#tableBody').append(row_html);
        $(`#product_id_${row_index}`).select2();
        $(`#unit_${row_index}`).select2();
        rows_id++;
        seqNo++;
        calculateTotal();
        let productSelect = document.getElementById(`product_id_${row_index}`);
        if (productSelect && productSelect.value) {
            sendUom(productSelect);
        }
        $(`#quantity_${row_index}`).on('input', function () {
            let materialLineId = $(this).data('materialdetailid');
            let enteredQty = $(this).val();
            let inputField = $(this);
            console.log("in the main"+materialLineId);
            // let movement_qty = $(`#movement_quantity_${row_index}`);

            if (materialLineId) {
                $.ajax({
                    url: '/check-available-MLquantity',
                    type: 'GET',
                    data: { material_line_id: materialLineId },
                    success: function (response) {
                        if (response.available_quantity !== undefined) {
                            let maxQuantity = response.available_quantity;
                            if (parseInt(enteredQty) > maxQuantity) {
                                inputField.val(maxQuantity); // Restrict to max available
                                // movement_qty.val(maxQuantity);
                                alert(`Maximum allowed quantity is ${maxQuantity}`);
                            }
                        }
                    }
                });
            }
        });
        return row_id;
        // checkSelect2Validity();

    }


    function openModal() {
        add_invoiceLine_row();
    }

    function deleteRow(row_id) {

        if (confirm("Are you sure you want to delete this row?")) {
            console.log('row id is:'+row_id);

            let structure_id = $('#row_id_' + row_id).val();

            console.log(structure_id);
            if (structure_id) {
                ffsQuiet($.post('/delete-inoutline-row/' + structure_id, {
                    "_token": "{{ csrf_token() }}",
                    "_method": "DELETE",
                })).then(function(data) {
                     // Check the response from the server
                    if (data.success) {
                        // Remove the row and show success message
                        $("#row_" + row_id).remove();
                        toastr.options = {
                            "positionClass": "toast-top-right",
                        };
                        toastr.success(data.message, 'Success');
                    } else if (data.error) {
                        // Show error message from the server
                        toastr.options = {
                            "positionClass": "toast-top-right",
                        };
                        toastr.error(data.message, 'Error');
                    }

                }).fail(function(xhr) {
                    toastr.options = {
                        "positionClass": "toast-top-right",
                    }
                    toastr.error(xhr.responseJSON.error, 'Error');

                })
            } else {
                $("#row_" + row_id).remove();
                toastr.options = {
                    "positionClass": "toast-top-right",
                }
                toastr.success("Row Removed Successfully", 'Success');

            }

        }


    }
    
    function change_name(inputField) {
    // Get the current value of the input field
        let newName = inputField.value.trim();
        let input_id = inputField.id;
        console.log("new name="+newName);
        console.log("input id="+input_id);
        let errorMessageElement = $('#'+input_id);
        errorMessageElement.text('');
        // Initialize a flag to check for duplicates
        let isDuplicate = false;

        // Iterate over all other name fields to check for duplicates
        $('input[name^="rows"][name$="[name]"]').each(function() {
            if ($(this).val().trim() === newName && this !== inputField) {
                isDuplicate = true;
                return false; // Exit loop if a duplicate is found
            }
        });

        // If a duplicate is found, alert the user and reset the value
        if (isDuplicate) {
            // errorMessageElement.text('name already exist');
            // msgboxbox.show("error",'The name '" + newName + "' already exists. Please use a unique name.', null);
            alert("The name '" + newName + "' already exists. Please use a unique name.");
            inputField.value = '';  // Optionally, reset the input field
        }
    }
    function change_number(inputField) {
    // Get the current value of the input field
        let newName = inputField.value.trim();
        let input_id = inputField.id;
        console.log("new name="+newName);
        console.log("input id="+input_id);
        let errorMessageElement = $('#'+input_id);
        errorMessageElement.text('');
        // Initialize a flag to check for duplicates
        let isDuplicate = false;

        // Iterate over all other name fields to check for duplicates
        $('input[name^="rows"][name$="[seq_no]"]').each(function() {
            if ($(this).val().trim() === newName && this !== inputField) {
                isDuplicate = true;
                return false; // Exit loop if a duplicate is found
            }
        });

        // If a duplicate is found, alert the user and reset the value
        if (isDuplicate) {
            // errorMessageElement.text('name already exist');
            // msgboxbox.show("error",'The name '" + newName + "' already exists. Please use a unique name.', null);
            alert("The number '" + newName + "' already exists. Please use a unique number.");
            inputField.value = '';  // Optionally, reset the input field
        }

    }
     // for saving uom automaticaly from product
     function sendUom(selectElement) {
        let rowIndex = selectElement.id.split("_").pop();
        let unitSelect = $(`#unit_${rowIndex}`);
        let selectedOption = selectElement.options[selectElement.selectedIndex];

        let unitId = selectedOption.getAttribute("data-unit"); // Get unit measure ID

        if (unitSelect) {
            unitSelect.val(unitId).trigger("change");
            // unitSelect.value = unitId; // Set the selected unit measure
        }
    }

    async function load_edit(){
        @foreach ($activity->invoiceLines as $row)
        // var activity = @json($activity);
            console.log("this is edit activity:", {!! $row !!});

            row_id = await add_invoiceLine_row({!! $row !!});
        @endforeach
    }

    $(document).ready(async function(){
        await load_edit();
        calculateTotal();
        flatpickr("#date_invoiced", {
            dateFormat: "Y-m-d",    // Format: YYYY-MM-DD
            allowInput: true,       // Allow manual input
            altInput: true,         // Show pretty UI
            altFormat: "F j, Y",    // Display format (e.g., August 29, 2023)
            disableMobile: true,    // Use desktop-style picker on mobile
            defaultDate: "{{ old('date_invoiced', $activity->date_invoiced) }}" // Pre-fill value
        });
        // flatpickr("#date_ordered", {
        //     dateFormat: "Y-m-d",    // Format: YYYY-MM-DD
        //     allowInput: true,       // Allow manual input
        //     altInput: true,         // Show pretty UI
        //     altFormat: "F j, Y",    // Display format (e.g., August 29, 2023)
        //     disableMobile: true,    // Use desktop-style picker on mobile
        //     defaultDate: "{{ old('date_ordered', $activity->date_ordered) }}" // Pre-fill value
        // });
        // flatpickr("#account_date", {
        //     dateFormat: "Y-m-d",    // Format: YYYY-MM-DD
        //     allowInput: true,       // Allow manual input
        //     altInput: true,         // Show pretty UI
        //     altFormat: "F j, Y",    // Display format (e.g., August 29, 2023)
        //     disableMobile: true,    // Use desktop-style picker on mobile
        //     defaultDate: "{{ old('account_date', $activity->account_date) }}" // Pre-fill value
        // });
    })

    $('#name').on('input', function() {
    var inputValue = $(this).val();
    // Remove non-numeric characters
    var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
    // Limit to exactly 11 numbers
    // var elevenDigitValue = numericValue.slice(0, 11);
    // Update the input field value
    $(this).val(numericValue);
    });
    $('#btn_draft').click(function() {
        $('#document_status_hidden').val('draft')
        calculate_total();
    })
    $('#btn_complete').click(function() {
        $('#document_status_hidden').val('completed')
        calculate_total();
    })
    // function calculateTotal() {
    //     let total = 0;
    //     $('.rate-input').each(function() {
    //         let value = parseFloat($(this).val());
    //         if (!isNaN(value)) {
    //             total += value;
    //         }
    //     });
    //     $('#booking_amount').val(total.toFixed(2));

    // }
    $('#document_action_btn').on('click', function () {
            $('#documentActionModal').modal('show');
        });

    // Handle "Draft" button click
    // $('#draftBtn').on('click', function () {
    //     // Set the hidden input value to "Draft"
    //     $('#document_action_input').val('draft');
    //     // Submit the form
    //     // $('#yourFormId').submit(); // Replace `yourFormId` with the actual ID of your form
    // });

    // Handle "Complete" button click
    $('#completeBtn').on('click', function () {
        // Set the hidden input value to "Complete"
        $('#document_action_input').val('completed');
        $('#document_status_hidden').val('completed')
        // Submit the form
        // $('#yourFormId').submit(); // Replace `yourFormId` with the actual ID of your form
    });
    // function calculateTotal() {
    //     let total = 0;

    //     // Loop through each row
    //     $('.rate-input').each(function () {
    //         let row = $(this).closest('tr'); // Get the current row
    //         let rate = parseFloat($(this).val()) || 0; // Get the rate value
    //         let quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0; // Get the quantity value
    //         let taxPercentage = parseFloat(row.find('input[name*="[tax]"]').val()) || 0; // Get the tax percentage value
    //         let discount =  0; // Get the discount value

    //         // Calculate subtotal (rate × quantity)
    //         let subtotal = rate * quantity;

    //         // Calculate tax amount (taxPercentage% of subtotal)
    //         let taxAmount = (subtotal * taxPercentage) / 100;

    //         // Calculate line amount (subtotal + tax - discount)
    //         let lineAmount = subtotal + taxAmount - discount;
    //         row.find('input[name*="[line_amount]"]').val(lineAmount.toFixed(2)); // Update line amount field

    //         // Calculate total line amount (line amount + tax amount)
    //         let totalLineAmount = lineAmount + taxAmount;
    //         row.find('input[name*="[total_line_amount]"]').val(totalLineAmount.toFixed(2)); // Update total line amount field

    //         // Add to the total booking amount
    //         if (!isNaN(totalLineAmount)) {
    //             total += totalLineAmount;
    //         }
    //     });

    //     // Update the total booking amount
    //     $('#total_amount').val(total.toFixed(2));
    // }
    function calculateTotal() {
    let totalWithoutTax = 0; // Total for booking_amount (without tax)
    let totalWithTax = 0;    // Total for final_amount (with tax)

    // Loop through each row
    $('.rate-input').each(function () {
        let row = $(this).closest('tr'); // Get the current row
        let rate = parseFloat($(this).val()) || 0; // Get the rate value
        let quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0; // Get the quantity value
        // let taxPercentage = parseFloat(row.find('input[name*="[tax]"]').val()) || 0; // Get the tax percentage value
        let taxPercentage = parseFloat(row.find('select[name*="[tax]"] option:selected').data('rate')) || 0;

        let discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0; // Get the discount value

        // Calculate subtotal (rate × quantity)
        let subtotal = rate * quantity;

        // Calculate tax amount (taxPercentage% of subtotal)
        let taxAmount = (subtotal * taxPercentage) / 100;
        // ✅ Update the hidden tax_value field
        row.find('input[name*="[tax_amount]"]').val(taxAmount.toFixed(2));
        // Calculate line amount (subtotal - discount) [WITHOUT TAX]
        let lineAmount = subtotal - discount;
        row.find('input[name*="[line_amount]"]').val(lineAmount.toFixed(2)); // Update line amount field

        // Calculate total line amount (line amount + tax amount) [WITH TAX]
        let totalLineAmount = lineAmount + taxAmount;
        row.find('input[name*="[total_line_amount]"]').val(totalLineAmount.toFixed(2)); // Update total line amount field

        // Accumulate totals
        totalWithoutTax += lineAmount;  // Sum of line amounts (without tax)
        totalWithTax += totalLineAmount; // Sum of total line amounts (with tax)
        });

        // Update total booking amount (without tax)
        $('#total_amount').val(totalWithoutTax.toFixed(2));

        // Update final amount (with tax)
        $('#grand_total_amount').val(totalWithTax.toFixed(2));
    }

    // Attach the calculateTotal function to relevant input fields
    $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[discount]"]', function () {
        calculateTotal();
    });
    $('body').on('change', 'select[name*="[tax]"]', function () {
        calculateTotal();
    });

    // Attach the calculateTotal function to relevant input fields
    $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[tax]"], input[name*="[tax_amount]"]', function () {
        calculateTotal();
    });

    // -----------------------------
    

</script>
<script>
    $(document).ready(function () {

        $('#business_partner_id').select2();
        $('#warehouse_id').select2();
        $('#material_inout_id').select2();
        $('#price_list_id').select2();
        $('#business_partner_id').change(function () {
            let partnerId = $(this).val();
            let orderDropdown = $('#material_inout_id');

            orderDropdown.html('<option value="">Loading...</option>'); // Show loading text

            if (partnerId) {
                $.ajax({
                    url: '{{ route("fetch.receipt") }}', // Laravel route
                    type: 'GET',
                    data: { business_partner_id: partnerId },
                    success: function (response) {
                        orderDropdown.empty().append('<option value="">-- Select --</option>');
                        console.log(response.data.length)
                        if (response.data.length > 0) {
                            $.each(response.data, function (key, receipt) {
                                orderDropdown.append('<option value="' + receipt.id + '">' + receipt.document_no + '</option>');
                            });
                        } else {
                            orderDropdown.append('<option value="">No orders found</option>');
                        }
                    },
                    error: function () {
                        orderDropdown.html('<option value="">Error fetching orders</option>');
                    }
                });
            } else {
                orderDropdown.html('<option value="">-- Select --</option>'); // Reset dropdown
            }
        });
        // --------------------------------
        // Handle the "Fetch Record" button click
        $('#fetchbtn').click(function() {
            // Get the selected material_inout_id from the dropdown
            var orderId = $('#material_inout_id').val();
            var business_partner_id = $('#business_partner_id').val();

            // Check if an order is selected
            if (!orderId || !business_partner_id) {
                msgboxbox.show('Please select a Material Receipt and business partner.', 'error', null);
                return;
            }

            // Send AJAX request
            ffsQuiet($.ajax({
                url: '{{ route("fetch.material.lines") }}',
                type: 'GET',
                data: {
                    material_inout_id: orderId,
                    business_partner_id:business_partner_id,
                },
                success: function(response) {
                    console.log(response); 
                    $('#material_lines_table tbody').empty(); 

                    if (response.data.length > 0) {
                        response.data.forEach(function(materialLine) {
                            add_invoiceLine_row({
                             
                                product_id: materialLine.product_id, // Product
                                // locator_id: materialLine.locator_id, // Locator
                                // description: materialLine.description, // Description
                                quantity: materialLine.quantity, // Quantity
                                order_detail_line_id: materialLine.order_detail_line_id,
                                material_inout_line_id: materialLine.id,
                                rate:materialLine.order_line.rate,
                                tax:materialLine.order_line.tax_id,
                                // unit:materialLine.product_id,
                                // picked_quantity: orderDetail.picked_quantity, // Picked Quantity
                                // target_quantity: orderDetail.target_quantity, // Target Quantity
                                // confirmed_quantity: orderDetail.confirmed_quantity, // Confirmed Quantity
                                // scrapped_quantity: orderDetail.scrapped_quantity // Scrapped Quantity
                            });
                        });
                        msgboxbox.show('Rows added successfully!', 'success', null); // Display success message
                    } else {
                        msgboxbox.show('No lines found for the selected material receipt.', 'error', null);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                    msgboxbox.show('An error occurred while fetching data..', 'error', null);
                }
            }));
        });

        $('#material_inout_id').on('change', function() {
            var orderId = $(this).val();
            if (orderId) {
                $.ajax({
                    url: "{{ route('get.receipt_order_id') }}", // Define this route in web.php
                    type: "GET",
                    data: { material_inout_id: orderId },
                    success: function(response) {
                        if (response.order_id) {
                            $('#order_id').val(response.order_id);
                        }
                    }
                });
            } else {
                $('#order_id').val(''); // Clear the field if no order is selected
            }
        });


        
    });
</script>
