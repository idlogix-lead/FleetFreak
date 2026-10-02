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
{{-- -------------------- --}}
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
                            <label for="picked_qty">Picked Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="picked_qty">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="target_qty">Target Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="target_qty">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="confirmed_qty">Confirmed Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="confirmed_qty">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="scrapped_qty">Scrapped Qty</label>
                            <input {{ $disabled }} type="number" class="form-control form-control-sm" id="scrapped_qty">
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-sm" for="locator_id"> Locator </label>
                            <select  name="locator_id" id="locator_id"
                                class="form-control red-border-select2 {{ $errors->has('locator_id') ? ' is-invalid' : '' }}">
                                <option value="">-- Select --</option>
                                @foreach (App\Models\Locator::locators() as $locator)
                                    <option value="{{ $locator->id }}"
                                        {{ old('locator_id', $activity->locator_id)== $locator->id ? 'selected' : '' }}>
                                        {{ Str::title($locator->locator_type) }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('locator_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>
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
            <div class="form-group">
                <label for="company_id">Company  </label>
                <input type="text" readonly name="company_id" class="form-control small-input form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
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
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="movement_date">Movement Date</label>
                <input {{ $disabled }} required type="date" name="movement_date" class="form-control small-input form-control-sm {{($errors->has('movement_date') ? ' is-invalid' : '')}}" id="movement_date" value="{{old('movement_date',$activity->movement_date)}}">
                {!! $errors->first('movement_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="warehouse_id">Warehouse</label>
                <select {{ $disabled }} name="warehouse_id" autofocus required id="warehouse_id"
                    class="form-control form-control-sm red-border-select2 {{ $errors->has('warehouse_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\WareHouse::warehouses() as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ $activity->warehouse_id== $warehouse->id ? 'selected' : '' }}>
                            {{ Str::title($warehouse->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="business_partner_id">Business Partner</label>
                <select {{ $disabled }} name="business_partner_id" autofocus required id="business_partner_id"
                    class="form-control form-control-sm red-border-select2 {{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Partner::vendorDropdown() as $business_partner)
                        <option value="{{ $business_partner->id }}"
                            {{ $activity->business_partner_id== $business_partner->id ? 'selected' : '' }}>
                            {{ Str::title($business_partner->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="order_id">Purchase Order</label>
                <select {{ $disabled }} name="order_id" id="order_id" autofocus required
                    class="form-control form-control-sm red-border-select2 {{ $errors->has('order_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @if ($activity->order_id)
                        <option value="{{ $activity->order_id }}" selected>
                            {{ $activity->order->order_no }}</option>
                    @endif
                </select>
                {!! $errors->first('order_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea {{ $disabled }} name="description" 
                    class="form-control small-input form-control-sm {{ $errors->has('description') ? ' is-invalid' : '' }}" 
                    id="description" rows="3">{{ old('description', $activity->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="delivery_man">Delivery Man</label>
                <input {{ $disabled }} type="text" placeholder="delivery_man" name="delivery_man" class="form-control small-input form-control-sm {{($errors->has('delivery_man') ? ' is-invalid' : '')}}" id="delivery_man" value="{{old('delivery_man',$activity->delivery_man)}}" autofocus>
                {!! $errors->first('delivery_man', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="delivery_vehicle">Delivery Vehicle</label>
                <input {{ $disabled }} type="text" placeholder="delivery_vehicle" name="delivery_vehicle" class="form-control small-input form-control-sm {{($errors->has('delivery_vehicle') ? ' is-invalid' : '')}}" id="delivery_vehicle" value="{{old('delivery_vehicle',$activity->delivery_vehicle)}}" autofocus>
                {!! $errors->first('delivery_vehicle', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="delivery_no">Delivery No</label>
                <input {{ $disabled }} type="text" placeholder="delivery_no" name="delivery_no" class="form-control small-input form-control-sm {{($errors->has('delivery_no') ? ' is-invalid' : '')}}" id="delivery_no" value="{{old('delivery_no',$activity->delivery_no)}}" autofocus>
                {!! $errors->first('delivery_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="po_reference">Order Ref</label>
                <input {{ $disabled }} type="text" placeholder="po_reference" name="po_reference" class="form-control small-input form-control-sm {{($errors->has('po_reference') ? ' is-invalid' : '')}}" id="po_reference" value="{{old('po_reference',$activity->po_reference)}}">
                {!! $errors->first('po_reference', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="rma">RMA</label>
                <input {{ $disabled }} type="text" name="rma" class="form-control {{($errors->has('rma') ? ' is-invalid' : '')}}" id="rma" value="{{old('rma',$activity->rma)}}">
                {!! $errors->first('rma', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="date_ordered">Fetch PO Date</label>
                <input {{ $disabled }} required type="date" placeholder="date_ordered" name="date_ordered" class="form-control small-input form-control-sm {{($errors->has('date_ordered') ? ' is-invalid' : '')}}" id="date_ordered" value="{{old('date_ordered',$activity->date_ordered)}}">
                {!! $errors->first('date_ordered', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="delivery_time">Delivery Time</label>
                <input autofocus required {{ $disabled }} type="time" placeholder="delivery_time" name="delivery_time" class="form-control small-input form-control-sm {{($errors->has('delivery_time') ? ' is-invalid' : '')}}" id="delivery_time" value="{{old('delivery_time',$activity->delivery_time)}}">
                {!! $errors->first('delivery_time', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
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
                        Fetch From PO
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
        <h6>Material InOut Line</h6>

    </div>
    <div class="table-responsive">
        <table id="material_lines_table" class="table new-table small">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Product <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                     <th>Locator <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{--<th>Description <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    <th>Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Movement Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                     {{-- <th>Picked Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    {{--<th>Target Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Confirmed Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Scrapped Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                    
                   
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
                'notify_btn' => 'Save as Draft',
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
    var row_index = -1;
    let rows_id = 1;
    let seqNo = 1;
    async function add_receiptLine_row(row={}){
        row_index = row_index+1;
        row_id = rows_id;
        // Use the current value of seqNo or the one provided in the row data
        let seqNoValue = row.seq_no ?? seqNo;

        let row_html = `
            <tr id='row_${row_id}'>

                <td>

                    <input type="hidden"  id="order_detail_line_id_${row_id}" value="${row.order_detail_line_id??""}" name="rows[${row_index}][order_detail_line_id]">
                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
                    <input  type="text" readonly required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control small-input form-control-sm"></td>
                    <input type="hidden" id="picked_qty_${row_id}" name="rows[${row_index}][picked_qty]" value="${row.picked_qty??""}">
                    <input type="hidden" id="target_qty_${row_id}" name="rows[${row_index}][target_qty]" value="${row.target_qty??""}">
                    <input type="hidden" id="confirmed_qty_${row_id}" name="rows[${row_index}][confirmed_qty]" value="${row.confirmed_qty??""}">
                    <input type="hidden" id="scrapped_qty_${row_id}" name="rows[${row_index}][scrapped_qty]" value="${row.scrapped_qty??""}">
                    <!--<input type="hidden" id="locator_id_${row_id}" name="rows[${row_index}][locator_id]" value="${row.locator_id??""}">-->
                <td style="min-width:200px;">
                    <select autofocus required {{ $disabled }} style="width:100%;" name="rows[${row_index}][product_id]" id="product_id_${row_index}" class="form-control small-input form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Product::dropdown() as $product)
                            <option value="{{ $product->id }}" ${'{{ $product->id }}' == row.product_id ? 'selected' : ''} >{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="min-width:200px;">
                    <select {{ $disabled }} autofocus required style="width:100%;" name="rows[${row_index}][locator_id]" id="locator_id_${row_index}" class="form-control small-input form-control-sm locator-dropdown" data-selected="${row.locator_id ?? ''}">
                        <option value=''>--Select--</option>
                        
                    </select>
                </td>
                <!--<td style="min-width:200px;">
                    <select {{ $disabled }} style="width:100%;" name="rows[${row_index}][locator_id]" id="locator_id_${row_index}" class="form-control small-input form-control-sm">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Locator::locators() as $locator)
                            <option value="{{ $locator->id }}" ${'{{ $locator->id }}' == row.locator_id ? 'selected' : ''} >{{ $locator->locator_type }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input {{ $disabled }}  type="text" value="${row.description??""}" name="rows[${row_index}][description]" id="description_${row_index}" class="form-control small-input form-control-sm"></td>-->
                <td><input {{ $disabled }}  type="number" required value="${row.quantity??""}" name="rows[${row_index}][quantity]" id="quantity_${row_index}" class="form-control small-input form-control-sm" oninput="movementQtyUpdate(this, ${row.quantity})" data-orderdetailid="${row.order_detail_line_id ?? ""}"></td>
                <td><input readonly  type="number" value="${row.movement_qty??""}" name="rows[${row_index}][movement_qty]" id="movement_quantity_${row_index}" class="form-control small-input form-control-sm"></td>

                <!--<td><input {{ $disabled }}  type="number" required value="${row.picked_qty??""}" name="rows[${row_index}][picked_quantity]" id="picked_quantity_${row_index}" class="form-control small-input form-control-sm"></td>
                <td><input {{ $disabled }}  type="number" value="${row.target_qty??""}" name="rows[${row_index}][target_quantity]" id="target_quantity_${row_index}" class="form-control"></td>
                <td><input {{ $disabled }}  type="number" value="${row.confirmed_qty??""}" name="rows[${row_index}][confirmed_quantity]" id="confirmed_quantity_${row_index}" class="form-control"></td>
                <td><input {{ $disabled }}  type="number" value="${row.scrapped_qty??""}" name="rows[${row_index}][scrapped_quantity]" id="scrapped_quantity_${row_index}" class="form-control"></td>
                -->

                <td>
                    <div class="d-flex align-items-center gap-3">
                        <!--<button type="button" class="btn btn-outline-primary btn-sm" onclick="openRowPopup('${row_id}')" data-toggle="tooltip" data-placement="left" title="Edit Row">
                            <i class="fas fa-edit"></i>
                        </button>-->
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
        $(`#locator_id_${row_index}`).select2();
        rows_id++;
        seqNo++;
        let productSelect = document.getElementById(`quantity_${row_index}`);
        if (productSelect && productSelect.value) {
            movementQtyUpdate(productSelect);
        }

        // updateLocatorDropdowns($('.locator-dropdown'), storedLocators); // Update new row
        updateLocatorDropdowns($('#locator_id_' + row_index), storedLocators);

        // calculateTotal();
        // Attach AJAX check on input event to restrict quantity to available
        $(`#quantity_${row_index}`).on('input', function () {
            let orderDetailId = $(this).data('orderdetailid');
            let enteredQty = $(this).val();
            let inputField = $(this);
            let movement_qty = $(`#movement_quantity_${row_index}`);

            if (orderDetailId) {
                $.ajax({
                    url: '/check-available-quantity',
                    type: 'GET',
                    data: { order_detail_id: orderDetailId },
                    success: function (response) {
                        if (response.available_quantity !== undefined) {
                            let maxQuantity = response.available_quantity;
                            if (parseInt(enteredQty) > maxQuantity) {
                                inputField.val(maxQuantity); // Restrict to max available
                                movement_qty.val(maxQuantity);
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
        add_receiptLine_row();
    }
    function openDetailsModal(row_id) {
        console.log(row_id);
        $('#modal_row_id').val(row_id);
        
        // Pre-fill modal fields with existing data from hidden inputs
     
        $('#picked_qty').val($(`#picked_qty_${row_id}`).val() ?? '');
        $('#target_qty').val($(`#target_qty_${row_id}`).val() ?? '');
        $('#confirmed_qty').val($(`#confirmed_qty_${row_id}`).val() ?? '');
        $('#scrapped_qty').val($(`#scrapped_qty_${row_id}`).val() ?? '');
        // $('#locator_id').val($(`#locator_id_${row_id}`).val() ?? '');

        $('#detailsModal').modal('show');
    }

    function saveDetails() {
        let row_id = $('#modal_row_id').val();
        console.log("Saving details for row: " + row_id);

       
        let picked_qty = $('#picked_qty').val();
        let target_qty = $('#target_qty').val();
        let confirmed_qty = $('#confirmed_qty').val();
        let scrapped_qty = $('#scrapped_qty').val();
        // let locator_id = $('#locator_id').val();

        // Directly update the hidden fields inside the row
      
        $(`#picked_qty_${row_id}`).val(picked_qty);
        $(`#target_qty_${row_id}`).val(target_qty);
        $(`#confirmed_qty_${row_id}`).val(confirmed_qty);
        $(`#scrapped_qty_${row_id}`).val(scrapped_qty);
        // $(`#locator_id_${row_id}`).val(locator_id);
        // calculateTotal();
        $('#detailsModal').modal('hide');
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
    let storedLocators = [];
    async function load_edit(){
        @foreach ($activity->materialInoutline as $row)
        // var activity = @json($activity);
            console.log("this is edit activity:", {!! $row !!});

            row_id = await add_receiptLine_row({!! $row !!});
        @endforeach
        let warehouseId = $('#warehouse_id').val();
        if (warehouseId) {
            $.ajax({
                url: '/get-locators/' + warehouseId, // Define this route in Laravel
                type: 'GET',
                success: function (response) {
                    storedLocators = response.locators; // Store locators globally
                    updateLocatorDropdowns($('.locator-dropdown'), storedLocators); // Call update for edit mode
                }
            });
        }
    }
    // let storedLocators = [];

    $(document).ready(async function(){
        await load_edit();
        $('#warehouse_id').change(function () {
            let warehouseId = $(this).val();
            if (warehouseId) {
                $.ajax({
                    url: '/get-locators/' + warehouseId, // Define this route in Laravel
                    type: 'GET',
                    success: function (response) {
                        storedLocators = response.locators; // Store locators in a global variable
                        updateLocatorDropdowns($('.locator-dropdown'), storedLocators);
                    }
                });
            }
        });
         // Variable to store locators
        // calculateTotal();
    })
    
    function updateLocatorDropdowns(locatorDropdowns, locators) {
        locatorDropdowns.each(function () {
            let locatorSelect = $(this);
            let selectedLocator = locatorSelect.attr('data-selected'); // Get selected value

            locatorSelect.empty().append('<option value="">--Select--</option>');
            locators.forEach(locator => {
                locatorSelect.append(`<option value="${locator.id}" ${locator.id == selectedLocator ? 'selected' : ''}>${locator.locator_type}</option>`);
            });
        });
    }

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
        // calculate_total();
    })
    // function movementQtyUpdate(quantity, maxQty = 0) {
    //     let rowIndex = quantity.id.split("_").pop();
    //     let unitSelect = document.getElementById(`movement_quantity_${rowIndex}`);

    //     unitSelect.value = quantity.value;

    //     // Validation: user can't enter more than maxQty
    //     let value = parseInt(quantity.value, 10);
    //     if (value > maxQty) {
    //         quantity.value = maxQty; // Restrict to max allowed
    //     } else if (value < 1) {
    //         quantity.value = 1; // Ensure at least 1 is entered
    //     }

    //     // Update movement_quantity and movementqty with the validated value
    //     unitSelect.value = quantity.value;
       
    // }

    function movementQtyUpdate(quantity, maxQty=0) {
        let rowIndex = quantity.id.split("_").pop();
        let unitSelect = document.getElementById(`movement_quantity_${rowIndex}`);
        unitSelect.value = quantity.value;

        // for validation user cant enter more then max_qty:
        // let value = parseInt(quantity.value, 10);
    
        // if (value > maxQty) {
        //     quantity.value = maxQty; // Restrict value to max allowed
        // } else if (value < 1) {
        //     quantity.value = 1; // Ensure at least 1 is entered
        // }
    }
    
    // $('#btn_complete').click(function() {
    //     $('#document_status_hidden').val('completed')
    //     // calculate_total();
    // })
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

    // Attach the calculateTotal function to relevant input fields
    // $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[tax]"], input[name*="[discount]"], input[name*="[tax_value]"]', function () {
    //     calculateTotal();
    // });

    // -----------------------------
    

</script>
<script>
    $(document).ready(function () {
        // Trigger the change event on the business partner dropdown if it has a value
        let partnerId = $('#business_partner_id').val();
        if (partnerId) {
            fetchPurchaseOrders(partnerId);
        }

        // Attach the change event listener to the business partner dropdown
        $('#business_partner_id').change(function () {
            let partnerId = $(this).val();
            fetchPurchaseOrders(partnerId);
        });
        $('#business_partner_id').select2({
            width:"100%",
        });
        $('#warehouse_id').select2({
            width:"100%",
        });
        $('#order_id').select2({
            width:"100%",
        });
        // $('#business_partner_id').change(function () {
        //     let partnerId = $(this).val();
        //     let orderDropdown = $('#order_id');

        //     orderDropdown.html('<option value="">Loading...</option>'); // Show loading text

        //     if (partnerId) {
        //         $.ajax({
        //             url: '{{ route("fetch.purchase.orders") }}', // Laravel route
        //             type: 'GET',
        //             data: { business_partner_id: partnerId },
        //             success: function (response) {
        //                 orderDropdown.empty().append('<option value="">-- Select --</option>');
        //                 console.log(response.data.length)
        //                 if (response.data.length > 0) {
        //                     $.each(response.data, function (key, order) {
        //                         orderDropdown.append('<option value="' + order.id + '">' + order.order_no + '</option>');
        //                     });
        //                 } else {
        //                     orderDropdown.append('<option value="">No orders found</option>');
        //                 }
        //             },
        //             error: function () {
        //                 orderDropdown.html('<option value="">Error fetching orders</option>');
        //             }
        //         });
        //     } else {
        //         orderDropdown.html('<option value="">-- Select --</option>'); // Reset dropdown
        //     }
        // });
        function fetchPurchaseOrders(partnerId) {
            let orderDropdown = $('#order_id');

            if (partnerId) {
                orderDropdown.html('<option value="">Loading...</option>'); // Show loading text

                $.ajax({
                    url: '{{ route("fetch.purchase.orders") }}', // Laravel route
                    type: 'GET',
                    data: { business_partner_id: partnerId },
                    success: function (response) {
                        orderDropdown.empty().append('<option value="">-- Select --</option>');
                        if (response.data.length > 0) {
                            $.each(response.data, function (key, order) {
                                orderDropdown.append('<option value="' + order.id + '">' + order.order_no + '</option>');
                            });

                            // Set the selected order in edit mode
                            let selectedOrderId = '{{ old("order_id", $activity->order_id ?? "") }}';
                            if (selectedOrderId) {
                                orderDropdown.val(selectedOrderId);
                            }
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
        }
        // --------------------------------
        // Handle the "Fetch Record" button click
        $('#fetchbtn').click(function() {
            // Get the selected order_id from the dropdown
            var orderId = $('#order_id').val();
            var business_partner_id = $('#business_partner_id').val();
            var warehouse_id = $('#warehouse_id').val();

            // Check if an order is selected
            if (!orderId || !business_partner_id || !warehouse_id) {
                msgboxbox.show('Please select purchase order,business partner and warehouse.', 'error', null);
                return;
            }

            // Send AJAX request
            ffsQuiet($.ajax({
                url: '{{ route("fetch.order.lines") }}',
                type: 'GET',
                data: {
                    order_id: orderId,
                    business_partner_id:business_partner_id,
                },
                success: function(response) {
                    console.log(response); 
                    $('#material_lines_table tbody').empty(); 

                    if (response.data.length > 0) {
                        response.data.forEach(function(orderDetail) {
                            add_receiptLine_row({
                             
                                product_id: orderDetail.product_id, // Product
                                locator_id: orderDetail.locator_id, // Locator
                                description: orderDetail.description, // Descriptions
                                quantity: orderDetail.order_qty, // its a order_qty of po lines
                                order_detail_line_id: orderDetail.id, // Quantity
                                // picked_quantity: orderDetail.picked_quantity, // Picked Quantity
                                // target_quantity: orderDetail.target_quantity, // Target Quantity
                                // confirmed_quantity: orderDetail.confirmed_quantity, // Confirmed Quantity
                                // scrapped_quantity: orderDetail.scrapped_quantity // Scrapped Quantity
                            });
                        });
                        msgboxbox.show('Rows added successfully!', 'success', null); // Display success message
                    } else {
                        msgboxbox.show('No order details found for the selected purchase order.', 'error', null);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                    msgboxbox.show('An error occurred while fetching data..', 'error', null);
                }
            }));
        });

        $('#order_id').on('change', function() {
            var orderId = $(this).val();
            if (orderId) {
                $.ajax({
                    url: "{{ route('get.purchase.order') }}", // Define this route in web.php
                    type: "GET",
                    data: { order_id: orderId },
                    success: function(response) {
                        if (response.date_ordered) {
                            $('#date_ordered').val(response.date_ordered);
                        }
                    }
                });
            } else {
                $('#date_ordered').val(''); // Clear the field if no order is selected
            }
        });


        
    });
</script>
{{-- @dd($activity->activityLines) --}}
