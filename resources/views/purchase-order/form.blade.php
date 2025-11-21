@php
    $disabled = '';
    if ($activity->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp


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
{{-- details popup model --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel">Additional Details</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <input type="hidden" id="modal_row_id">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="unit_price">Unit Price</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="unit_price">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="list_price">List Price</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="list_price">
                        </div>
                    </div>
                </div>
            
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount">Discount</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="discount">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tax_value">Tax Value</label>
                            <input readonly {{ $disabled }} type="number" class="form-control form-control-sm" id="tax_value">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_ordered_line">Date Ordered</label>
                            <input {{ $disabled }} type="date" class="form-control form-control-sm" id="date_ordered_line">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_promised_line">Date Promised</label>
                            <input {{ $disabled }} type="date" class="form-control form-control-sm" id="date_promised_line">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="order_qty">Order Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="order_qty">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="delivered_qty">Delivered Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="delivered_qty">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reserved_qty">Reserved Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="reserved_qty">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="invoiced_qty">Invoiced Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="invoiced_qty">
                        </div>
                    </div>
                </div>
                {{-- <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <input type="text" class="form-control" id="remarks">
                </div>
                <div class="form-group">
                    <label for="custom_field">Custom Field</label>
                    <input type="text" class="form-control" id="custom_field">
                </div> --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveDetails()">Save</button>
            </div>
        </div>
    </div>
</div>

{{-- end --}}
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="company_id">Company  </label>
                <input type="text" readonly name="company_id" class="form-control small-input form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="document_no">Document No</label>
                <input readonly type="text" name="document_no" class="form-control small-input form-control-sm {{($errors->has('document_no') ? ' is-invalid' : '')}}" id="document_no" value="{{$activity->order_no}}">
                {!! $errors->first('document_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
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
            <div class="form-group small-font">
                <label class="text-sm" for="document_action">Document Action</label>
                <button type="button" {{$disabled}} class="btn btn-primary btn-sm w-100" id="document_action_btn">
                    {{ $activity->document_action ?? 'Action' }}
                </button>
                <input type="hidden" name="document_action" id="document_action_input" value="{{ $activity->document_action }}">
            </div>
        </div>    
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="date_ordered">Date Ordered</label>
                <input {{ $disabled }} type="date" placeholder="date_ordered" name="date_ordered" class="form-control small-input form-control-sm {{($errors->has('date_ordered') ? ' is-invalid' : '')}}" id="date_ordered" value="{{ old('date_ordered', $activity->date_ordered ?? now()->format('Y-m-d')) }}" autofocus required>
                {!! $errors->first('date_ordered', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="date_promised">Date Promised</label>
                <input {{ $disabled }} type="date" placeholder="date_promised" name="date_promised" class="form-control small-input form-control-sm {{($errors->has('date_promised') ? ' is-invalid' : '')}}" id="date_promised" value="{{old('date_promised',$activity->date_promised)}}" autofocus required>
                {!! $errors->first('date_promised', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="warehouse_id">Warehouse</label>
                <select {{ $disabled }} name="warehouse_id" autofocus required id="warehouse_id"
                    class="form-control red-border-select2 {{ $errors->has('warehouse_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\WareHouse::warehouses() as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id', $activity->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                            {{ Str::title($warehouse->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="business_partner_id">Business Partner</label>
                <select {{ $disabled }} name="business_partner_id" autofocus required id="business_partner_id" onchange="sendPriceList(this)"
                    class="form-control red-border-select2 {{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Partner::vendorDropdown() as $business_partner)
                        <option value="{{ $business_partner->id }}"  data-price_list_id="{{ $business_partner->price_list_id }}"
                            {{ old('business_partner_id', $activity->business_partner_id) == $business_partner->id ? 'selected' : '' }}>

                            {{ Str::title($business_partner->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="price_list">Price List</label>
                <select {{ $disabled }} name="price_list" autofocus required id="price_list" onchange="updateCurrency()"
                    class="form-control red-border-select2 {{ $errors->has('price_list') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\PriceList::pricelist() as $pricelist)
                        <option value="{{ $pricelist->id }}" data-currency="{{ $pricelist->currency }}"
                            {{ old('price_list_id', $activity->price_list_id) == $pricelist->id ? 'selected' : '' }}>
                            {{ Str::title($pricelist->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('price_list', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="price_list">Price List</label>
                <input {{ $disabled }} type="text" placeholder="price_list" name="price_list" class="form-control {{($errors->has('price_list') ? ' is-invalid' : '')}}" id="price_list" value="{{old('price_list',$activity->price_list)}}" autofocus>
                {!! $errors->first('price_list', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="currency">Currency</label>
                <input readonly {{ $disabled }} type="text" placeholder="Ex: Euro" name="currency" class="form-control small-input form-control-sm {{($errors->has('currency') ? ' is-invalid' : '')}}" id="currency" value="{{old('currency',$activity->currency)}}" autofocus>
                {!! $errors->first('currency', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="payment_term">Payment Term</label>
                <input {{ $disabled }} type="text" name="payment_term" class="form-control small-input form-control-sm {{($errors->has('payment_term') ? ' is-invalid' : '')}}" id="payment_term" value="{{old('payment_term',$activity->payment_term)}}" autofocus>
                {!! $errors->first('payment_term', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="po_reference">Order Ref</label>
                <input {{ $disabled }} type="text" name="po_reference" class="form-control small-input form-control-sm {{($errors->has('po_reference') ? ' is-invalid' : '')}}" id="po_reference" value="{{old('po_reference',$activity->po_reference)}}" autofocus>
                {!! $errors->first('po_reference', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="invoice_partner_id">Invoice Partner</label>
                <select {{ $disabled }} name="invoice_partner_id" autofocus required id="invoice_partner_id"
                    class="form-control red-border-select2 {{ $errors->has('invoice_partner_id') ? ' is-invalid' : '' }}" autofocus required>
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
        
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group small-font">
                <label for="description">Description</label>
                <input {{ $disabled }} type="text" name="description" class="form-control small-input form-control-sm {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$activity->description)}}" autofocus>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
      
        <div class="c_cols col-sm-6 col-md-4 col-lg-3">
            <div class="form-group top-card-form-group small-font">
                <label for="booking_amount">Booking Amount </label>
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
                    <input {{ $disabled }} type="text" readonly value="{{ $activity->booking_amount }}"
                        name="booking_amount"
                        class="form-control small-input form-control-sm {{ $errors->has('booking_amount') ? ' is-invalid' : '' }}"
                        id="booking_amount" autofocus>
                    {!! $errors->first('booking_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        <div class="c_cols col-sm-6 col-md-4 col-lg-3">
            <div class="form-group top-card-form-group small-font">
                <label for="final_amount">Final Amount </label>
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
                    <input {{ $disabled }} type="text" readonly value="{{ $activity->final_amount }}"
                        name="final_amount"
                        class="form-control small-input form-control-sm {{ $errors->has('final_amount') ? ' is-invalid' : '' }}"
                        id="final_amount" autofocus>
                    {!! $errors->first('final_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
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
            <button type="button" class="btn btn-outline-primary float-end btn-sm" onclick="openModal()" data-toggle="tooltip" data-placement="left" title="Add Row">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            </button> 
            @endif
        {{-- </div> --}}
        <h6>Purchase Order Lines</h6>

    </div>
    <div class="table-responsive">
        <table class="table new-table table-sm small ">
            <thead>
                <tr>

                    <th>Seq No</th>
                    {{-- <th>Date Ordered <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Date Promised <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    
                    <th>Product <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    <th>Unit Measure <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    <th>Quantity <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    {{-- <th>Order Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    {{-- <th>Delivered Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Reserved Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Invoiced Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Rate <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    {{-- <th>Unit Price <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>List Price <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Tax <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    {{-- <th>Discount <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Tax Value <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Line Amount <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                    <th>Total Amount <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span> --}}
                    </th>
                   
                    {{-- <th>Adult</th> --}}
                    {{-- <th>Child</th> --}}
                            {{-- <th>Function</th>
                    <th>Return</th> --}}
                    {{-- <th>Bags</th> --}}
                    <th>Actions <span>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
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

            // $model2 = [
            //     'notify_btn' => 'Completed',
            //     'function' => 'Save',
            //     'body' => 'Please Confirm do you realy want to Save?',
            //     'btn-color' => 'success',
            //     'float' => 'end mt-2',
            //     'id' => 'complete',
            // ];
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
    async function add_po_line_row(row={}){
        row_index = row_index+1;
        row_id = rows_id;
        // Use the current value of seqNo or the one provided in the row data
        let seqNoValue = row.seq_no ?? seqNo;

        let row_html = `
            <tr id='row_${row_id}'>

                <td>
                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]">
                    <input  type="text" readonly required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control small-input form-control-sm">
                    <input type="hidden" {{$disabled}} value="${oldValues?.rows?.[row_index]?.date_ordered ?? row?.date_ordered ?? ''}" name="rows[${row_index}][date_ordered]" id="date_ordered_${row_id}" class="form-control small-input form-control-sm">
                    <input type="hidden" {{$disabled}} value="${oldValues?.rows?.[row_index]?.date_promised ?? row?.date_promised ?? ''}" name="rows[${row_index}][date_promised]" id="date_promised_${row_id}" class="form-control small-input form-control-sm">
                     <!-- Adding additional extra hidden fields here -->
                    <input type="hidden" id="unit_price_${row_id}" name="rows[${row_index}][unit_price]" value="${oldValues?.rows?.[row_index]?.unit_price ?? row?.unit_price ?? ''}">
                    <input type="hidden" id="list_price_${row_id}" name="rows[${row_index}][list_price]" value="${oldValues?.rows?.[row_index]?.list_price ?? row?.list_price ?? ''}">
                    <input type="hidden" id="discount_${row_id}" name="rows[${row_index}][discount]" value="${oldValues?.rows?.[row_index]?.discount ?? row?.discount ?? ''}">
                    <input type="hidden" id="tax_value_${row_id}" name="rows[${row_index}][tax_value]" value="${oldValues?.rows?.[row_index]?.tax_value ?? row?.tax_value ?? ''}">
                    <input type="hidden" id="order_qty_${row_id}" name="rows[${row_index}][order_qty]" value="${oldValues?.rows?.[row_index]?.order_qty ?? row?.order_qty ?? ''}">
                    <input type="hidden" id="delivered_qty_${row_id}" name="rows[${row_index}][delivered_qty]" value="${oldValues?.rows?.[row_index]?.delivered_qty ?? row?.delivered_qty ?? ''}">
                    <input type="hidden" id="reserved_qty_${row_id}" name="rows[${row_index}][reserved_qty]" value="${oldValues?.rows?.[row_index]?.reserved_qty ?? row?.reserved_qty ?? ''}">
                    <input type="hidden" id="invoiced_qty_${row_id}" name="rows[${row_index}][invoiced_qty]" value="${oldValues?.rows?.[row_index]?.invoiced_qty ?? row?.invoiced_qty ?? ''}">
                </td>
                <!--<td><input type="date" {{$disabled}} required value="${oldValues?.rows?.[row_index]?.date_ordered ?? row?.date_ordered ?? ''}" name="rows[${row_index}][date_ordered]" id="date_ordered_${row_index}" class="form-control small-input form-control-sm"></td>
                <td><input type="date" {{$disabled}} required value="${oldValues?.rows?.[row_index]?.date_promised ?? row?.date_promised ?? ''}" name="rows[${row_index}][date_promised]" id="date_promised_${row_index}" class="form-control small-input form-control-sm"></td>
                -->
                
                <td style="min-width:150px;">
                    <select style="width:100%;" {{$disabled}} name="rows[${row_index}][product_id]" id="product_id_${row_index}" class="form-control small-input form-control-sm" onchange="fetchProductPrice(${row_index})">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Product::dropdown() as $product)
                            <option value="{{ $product->id }}" ${oldValues?.rows?.[row_index]?.product_id == {{ $product->id }} ? 'selected' : row?.product_id == {{ $product->id }} ? 'selected' : ''} data-unit="{{ $product->unit_measure_id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="min-width:100px;">
                    <select style="width:100%;" {{$disabled}} name="rows[${row_index}][unit]" id="unit_${row_index}" class="form-control form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\UnitMeasure::unit_type() as $unit)
                            <option value="{{ $unit->id }}" ${oldValues?.rows?.[row_index]?.unit == {{ $unit->id }} ? 'selected' : row?.unit == {{ $unit->id }} ? 'selected' : ''}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" {{$disabled}} required value="${oldValues?.rows?.[row_index]?.quantity ?? row?.quantity ?? ''}" placeholder="Ex: 10" name="rows[${row_index}][quantity]" id="quantity_${row_index}" class="form-control small-input form-control-sm" oninput="updateHiddenQty(this)"></td>
                <!--<td><input type="number" readonly value="${oldValues?.rows?.[row_index]?.order_qty ?? row?.order_qty ?? ''}" name="rows[${row_index}][order_qty]" id="order_qty_${row_index}" class="form-control small-input form-control-sm"></td>-->
                <!--<td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.delivered_qty ?? row?.delivered_qty ?? ''}" name="rows[${row_index}][delivered_qty]" id="delivered_qty_${row_index}" class="form-control"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.reserved_qty ?? row?.reserved_qty ?? ''}" name="rows[${row_index}][reserved_qty]" id="reserved_qty_${row_index}" class="form-control"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.invoiced_qty ?? row?.invoiced_qty ?? ''}" name="rows[${row_index}][invoiced_qty]" id="invoiced_qty_${row_index}" class="form-control"></td>
                -->
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.rate ?? row?.rate ?? ''}" name="rows[${row_index}][rate]" id="rate_${row_index}" class="form-control rate-input small-input form-control-sm" oninput="calculateTotal()"></td>
                <!--<td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.unit_price ?? row?.unit_price ?? ''}" name="rows[${row_index}][unit_price]" id="unit_price_${row_index}" class="form-control small-input form-control-sm"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.list_price ?? row?.list_price ?? ''}" name="rows[${row_index}][list_price]" id="list_price_${row_index}" class="form-control small-input form-control-sm"></td>-->
                <td style="min-width:100px;">
                    <select style="width:100%;" {{$disabled}} name="rows[${row_index}][tax]" id="tax_${row_index}" class="form-control form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Tax::Taxes() as $tax)
                            <option value="{{ $tax->id }}" data-rate="{{ $tax->rate }}" ${oldValues?.rows?.[row_index]?.tax == {{ $tax->id }} ? 'selected' : row?.tax == {{ $tax->id }} ? 'selected' : ''}>{{ $tax->name }}</option>
                        @endforeach
                    </select>
                </td>
                <!--<td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.tax ?? row?.tax ?? ''}" name="rows[${row_index}][tax]" id="tax_${row_index}" class="form-control small-input form-control-sm"></td>-->
                <!--<td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.discount ?? row?.discount ?? ''}" name="rows[${row_index}][discount]" id="discount_${row_index}" class="form-control small-input form-control-sm"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.tax_value ?? row?.tax_value ?? ''}" name="rows[${row_index}][tax_value]" id="tax_value_${row_index}" class="form-control small-input form-control-sm"></td>
                -->
               
                <td><input readonly type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.line_amount ?? row?.line_amount ?? ''}" name="rows[${row_index}][line_amount]" id="line_amount_${row_index}" class="form-control small-input form-control-sm"></td>
                <td><input readonly type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.total_line_amount ?? row?.total_line_amount ?? ''}" name="rows[${row_index}][total_line_amount]" id="total_line_amount_${row_index}" class="form-control small-input form-control-sm"></td>

                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="text-danger rm-row mt-1" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea356f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </div>
                        <div class="text-info details-row mt-1" onclick="openDetailsModal(${row_id})" data-toggle="tooltip" data-placement="left" title="Extra Details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#17a2b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                    <path d="M4 4v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6c-1.1 0-2 .9-2 2z"></path>
                                    <path d="M14 3v5h5"></path>
                                    <line x1="8" y1="13" x2="16" y2="13"></line>
                                    <line x1="8" y1="17" x2="16" y2="17"></line>
                                    <line x1="8" y1="9" x2="16" y2="9"></line>
                                </svg>
                        </div>           
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
        return row_id;
        // checkSelect2Validity();

    }


    function openModal() {
        let po_date_ordered = $('#date_ordered').val();
        let po_date_promised = $('#date_promised').val();
        add_po_line_row({date_ordered:po_date_ordered,date_promised:po_date_promised});
    }

    function openDetailsModal(row_id) {
        console.log(row_id);
        $('#modal_row_id').val(row_id);
        
        // Pre-fill modal fields with existing data from hidden inputs
        $('#unit_price').val($(`#unit_price_${row_id}`).val() ?? '');
        $('#list_price').val($(`#list_price_${row_id}`).val() ?? '');
        $('#discount').val($(`#discount_${row_id}`).val() ?? '');
        $('#tax_value').val($(`#tax_value_${row_id}`).val() ?? '');
        $('#date_ordered_line').val($(`#date_ordered_${row_id}`).val() ?? '');
        $('#date_promised_line').val($(`#date_promised_${row_id}`).val() ?? '');
        $('#order_qty').val($(`#order_qty_${row_id}`).val() ?? '');
        $('#delivered_qty').val($(`#delivered_qty_${row_id}`).val() ?? '');
        $('#reserved_qty').val($(`#reserved_qty_${row_id}`).val() ?? '');
        $('#invoiced_qty').val($(`#invoiced_qty_${row_id}`).val() ?? '');

        $('#detailsModal').modal('show');
    }

    function saveDetails() {
        let row_id = $('#modal_row_id').val();
        console.log("Saving details for row: " + row_id);

        let list_price = $('#list_price').val();
        let unit_price = $('#unit_price').val();
        let discount = $('#discount').val();
        let tax_value = $('#tax_value').val();
        let date_ordered = $('#date_ordered_line').val();
        let date_promised = $('#date_promised_line').val();
        let order_qty = $('#order_qty').val();
        let delivered_qty = $('#delivered_qty').val();
        let reserved_qty = $('#reserved_qty').val();
        let invoiced_qty = $('#invoiced_qty').val();

        // Directly update the hidden fields inside the row
        $(`#unit_price_${row_id}`).val(unit_price);
        $(`#list_price_${row_id}`).val(list_price);
        $(`#discount_${row_id}`).val(discount);
        $(`#tax_value_${row_id}`).val(tax_value);
        $(`#date_ordered_${row_id}`).val(date_ordered);
        $(`#date_promised_${row_id}`).val(date_promised);
        $(`#order_qty_${row_id}`).val(order_qty);
        $(`#delivered_qty_${row_id}`).val(delivered_qty);
        $(`#reserved_qty_${row_id}`).val(reserved_qty);
        $(`#invoiced_qty_${row_id}`).val(invoiced_qty);
        calculateTotal();
        $('#detailsModal').modal('hide');
    }




    function deleteRow(row_id) {

        if (confirm("Are you sure you want to delete this row?")) {
            console.log('row id is:'+row_id);

            let structure_id = $('#row_id_' + row_id).val();

            console.log(structure_id);
            if (structure_id) {
                $.post('/delete-poline-row/' + structure_id, {
                    "_token": "{{ csrf_token() }}",
                    "_method": "DELETE",
                }).then(function(data) {
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
    function sendPriceList(selectElement) {
        let selectedOption = selectElement.options[selectElement.selectedIndex]; // Get selected option
        let priceListId = selectedOption.getAttribute("data-price_list_id"); // Get price list ID
        let priceListSelect = document.getElementById("price_list"); // Get Price List dropdown

        if (priceListSelect) {
                $(priceListSelect).val(priceListId).trigger("change");
            }
        else {
            priceListSelect.value = ""; // Reset if not found
            $(priceListSelect).val("").trigger("change"); // Reset Select2 UI
        }
    }
    function updateCurrency() {
        let priceListSelect = document.getElementById("price_list");
        let selectedOption = priceListSelect.options[priceListSelect.selectedIndex]; // Get selected option
        let currency = selectedOption.getAttribute("data-currency"); // Get currency value
        document.getElementById("currency").value = currency || ""; // Set currency input value
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
    // function sendUom(selectElement) {
    //     let rowIndex = selectElement.id.split("_").pop();
    //     let unitSelect = document.getElementById(`unit_${rowIndex}`);
    //     let selectedOption = selectElement.options[selectElement.selectedIndex];

    //     let unitId = selectedOption.getAttribute("data-unit"); // Get unit measure ID
    //     console.log();
    //     if (unitSelect) {
    //         unitSelect.value = unitId; // Set the selected unit measure
    //     }
    // }
    function updateHiddenQty(input) {
        console.log(input);
        let rowIndex = input.id.split("_").pop(); // Extract row index
        let hiddenInput = document.getElementById(`order_qty_${rowIndex}`);
        
        if (hiddenInput) {
            hiddenInput.value = input.value; // Set hidden field value to match quantity
        }
    }
    // -------------------------------

    async function load_edit(){
        @foreach ($activity->orderDetails as $row)
        // var activity = @json($activity);
            console.log("this is edit activity:", {!! $row !!});

            row_id = await add_po_line_row({!! $row !!});
        @endforeach
    }

    $(document).ready(async function(){
        await load_edit();
        calculateTotal();
        if (oldValues.length > 0) {
            oldValues.forEach(row => add_po_line_row(row));
    }
        $('#business_partner_id').select2({
            width:"100%",
        });
        $('#invoice_partner_id').select2({
            width:"100%",
        });
        $('#warehouse_id').select2({
            width:"100%",
        });
        $('#price_list').select2({
            width:"100%",
        });
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
    // $('#btn_complete').click(function() {
    //     $('#document_status_hidden').val('completed')
    //     calculate_total();
    // })
     // Open the modal when the "Document Action" button is clicked
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
    //     $('.rate-input').each(function() {
    //         let value = parseFloat($(this).val());
    //         if (!isNaN(value)) {
    //             total += value;
    //         }
    //     });
    //     $('#booking_amount').val(total.toFixed(2));

    // }
    // function calculateTotal() {
    //     let total = 0;

    //     // Loop through each row
    //     $('.rate-input').each(function () {
    //         let row = $(this).closest('tr'); // Get the current row
    //         let rate = parseFloat($(this).val()) || 0; // Get the rate value
    //         let quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0; // Get the quantity value
    //         let tax = parseFloat(row.find('input[name*="[tax]"]').val()) || 0; // Get the tax value
    //         let discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0; // Get the discount value
    //         let taxValue = parseFloat(row.find('input[name*="[tax_value]"]').val()) || 0; // Get the tax value

    //         // Calculate line amount
    //         let lineAmount = (rate * quantity) + tax - discount;
    //         row.find('input[name*="[line_amount]"]').val(lineAmount.toFixed(2)); // Update line amount field

    //         // Calculate total line amount
    //         let totalLineAmount = lineAmount + taxValue;
    //         row.find('input[name*="[total_line_amount]"]').val(totalLineAmount.toFixed(2)); // Update total line amount field

    //         // Add to the total booking amount
    //         if (!isNaN(totalLineAmount)) {
    //             total += totalLineAmount;
    //         }
    //     });

    //     // Update the total booking amount
    //     $('#booking_amount').val(total.toFixed(2));
    // }
    // function calculateTotal() {
    //     let total = 0;

    //     // Loop through each row
    //     $('.rate-input').each(function () {
    //         let row = $(this).closest('tr'); // Get the current row
    //         let rate = parseFloat($(this).val()) || 0; // Get the rate value
    //         let quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0; // Get the quantity value
    //         let taxPercentage = parseFloat(row.find('input[name*="[tax]"]').val()) || 0; // Get the tax percentage value
    //         let discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0; // Get the discount value

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
    //     $('#booking_amount').val(total.toFixed(2));
    // }

    // // Attach the calculateTotal function to relevant input fields
    // $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[tax]"], input[name*="[discount]"], input[name*="[tax_value]"]', function () {
    //     calculateTotal();
    // });
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
        row.find('input[name*="[tax_value]"]').val(taxAmount.toFixed(2));
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
        $('#booking_amount').val(totalWithoutTax.toFixed(2));

        // Update final amount (with tax)
        $('#final_amount').val(totalWithTax.toFixed(2));
    }

    // Attach the calculateTotal function to relevant input fields
    $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[discount]"]', function () {
        calculateTotal();
    });
    $('body').on('change', 'select[name*="[tax]"]', function () {
        calculateTotal();
    });

    function fetchProductPrice(rowIndex) {
            let productId = $(`#product_id_${rowIndex}`).val(); // Get the selected product ID
            let rateField = $(`#rate_${rowIndex}`); // Get the rate field for the current row
            let unitField = $(`#unit_${rowIndex}`);

            if (productId) {
                $.ajax({
                    url: '{{ route("fetch.product.price") }}', // Laravel route to fetch product price
                    type: 'GET',
                    data: { product_id: productId },
                    success: function (response) {
                        if (response.success && response.price && response.unit) {
                            rateField.val(response.price); // Update the rate field with the fetched price
                            unitField.val(response.unit).trigger("change");

                            // unitField.val(response.unit);
                            calculateTotal(); // Recalculate totals if needed
                        } else {
                            rateField.val(''); // Clear the rate field if no price is found
                        }
                    },
                    error: function () {
                        rateField.val(''); // Clear the rate field on error
                        alert('Error fetching product price. Please try again.');
                    }
                });
            } else {
                rateField.val(''); // Clear the rate field if no product is selected
            }
    }
    function printOrderDetails(orderId) {
        // Open a new window or tab with print content
        const printWindow = window.open(`/print-order/${orderId}`, '_blank');
        
        // If you want the print dialog to appear automatically
        printWindow.onload = function () {
            printWindow.print();
        };
    }

</script>   
{{-- @dd($activity->activityLines) --}}
