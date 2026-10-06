@php
    $disabled = '';
    if ($activity->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp

<!-- Modal -->
{{-- <div class="modal fade" id="documentActionModal" tabindex="-1" aria-labelledby="documentActionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header">
                <h5 class="modal-title" id="documentActionModalLabel">Select Document Action</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Choose an action for the document:</p>
                <button type="submit" class="btn btn-secondary" id="draftBtn">Draft</button>
                <button type="submit" class="btn btn-primary" id="completeBtn">Complete</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal -->
<!-- Modal -->
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
                <p class="mb-3 text-muted">Choose an action for the document:</p>
                
                <!-- Buttons in a single row -->
                <div class="d-flex justify-content-center gap-2">
                    <button type="submit" class="btn btn-outline-secondary btn-sm d-flex align-items-center" id="draftBtn">
                        <i class="bi bi-pencil-square me-1"></i> Draft
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center" id="completeBtn">
                        <i class="bi bi-check-circle me-1"></i> Complete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="company_id">Company  </label>     
                <input type="text" readonly name="company_id" class="form-control small-input form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_no">Document No</label>
                <input readonly type="text" name="document_no" class="form-control small-input form-control-sm {{($errors->has('document_no') ? ' is-invalid' : '')}}" id="document_no" value="{{old('document_no',$activity->document_no)}}">
                {!! $errors->first('document_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_type">Document Type</label>
                <input readonly type="text" name="document_type" class="form-control small-input form-control-sm {{($errors->has('document_type') ? ' is-invalid' : '')}}" id="document_type" value="Inventory Move">
                {!! $errors->first('document_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_status">Document Status</label>
                <input type="hidden" name="document_status" id="document_status_hidden"
                    value="{{ $activity->document_status ? $activity->document_status : 'draft' }}">
                <input type="text" {{ $disabled }} readonly placeholder="Document Status"
                    class="form-control small-input form-control-sm {{ $errors->has('document_status') ? ' is-invalid' : '' }}"
                    id="document_status"
                    value="{{ $activity->document_status ? ucfirst($activity->document_status) : 'Draft' }}">
                {!! $errors->first('document_status', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="document_action">Document Action</label>
                <button type="button" class="btn btn-primary btn-sm w-100" id="document_action_btn">
                    {{ $activity->document_action ?? 'Action' }}
                </button>
                <input type="hidden" name="document_action" id="document_action_input" value="{{ $activity->document_action }}">
            </div>
        </div>        
        
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="inventory_date">Inventory Date</label>
                <input {{ $disabled }} autofocus required type="date" name="inventory_date" class="form-control small-input form-control-sm {{($errors->has('inventory_date') ? ' is-invalid' : '')}}" id="inventory_date" value="{{old('inventory_date',$activity->inventory_date)}}">
                {!! $errors->first('inventory_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
       
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="warehouse_id">Warehouse</label>
                <select {{ $disabled }} name="warehouse_id" required id="warehouse_id"
                    class="form-control red-border-select2 {{ $errors->has('warehouse_id') ? ' is-invalid' : '' }}" autofocus>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\WareHouse::warehouses() as $warehouse)
                        <option value="{{ $warehouse->id }}"
                            {{ old('warehouse_id', $activity->warehouse_id)== $warehouse->id ? 'selected' : '' }}>
                            {{ Str::title($warehouse->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="document_action">Document Action</label>
                <input {{ $disabled }} type="text" placeholder="document_action" name="document_action" class="form-control {{($errors->has('document_action') ? ' is-invalid' : '')}}" id="document_action" value="{{$activity->document_action}}">
                {!! $errors->first('document_action', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
       
       
        <div class="col-sm-12 col-md-9 col-lg-9">
            <div class="form-group">
                <label class="text-sm" for="description">Description</label>
                <textarea {{ $disabled }} name="description" 
                    class="form-control form-control-sm {{ $errors->has('description') ? ' is-invalid' : '' }}" 
                    id="description" rows="3">{{ old('description', $activity->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($activity) && $activity->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div> --}}
        <div class="col-md-3 my-4">
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
                <label class="form-check-label" for="is_active" style="margin-left: 10px;">Is Active</label>
            </div>
        </div>
        {{-- @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\InventoryMove::where('company_id',auth()->user()->active_company())->where('is_active',1)->where('is_default', 1)->exists();
        @endphp
    
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">
                
                <!-- Toggle Switch -->
                <label class="switch">
                    <input type="checkbox" value="1" name="is_default" id="is_default"
                        {{ old('is_default', $activity->is_default) ? 'checked' : '' }}
                        @if($isActiveDisabled && $activity->is_default == 0) disabled @endif>
                    <span class="slider round"></span>
                </label>
                
                <!-- Label for the toggle -->
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div> --}}

        {{-- <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">
                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default"
                    {{ old('is_default', $activity->is_default) ? 'checked' : '' }}
                    @if($isActiveDisabled && $activity->is_default== 0) disabled @endif>
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div>   --}}

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
            <br>
            @endif
        {{-- </div> --}}
        <h6>Physical Inv Lines</h6>

    </div>
    <div class="table-responsive">
        <table class="table new-table table-sm small">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Product <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Locator <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Description <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>System Qty <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Physical Qty <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Adjusted Qty <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Is Active <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    {{-- <th>Order Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Delivered Quantity <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Reserved Quantity <span>
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
        @include('partials.modal', ['data' => $model])
        @include('partials.modal', ['data' => $model2])
    @endif --}}
</div>
<script>
    var oldValues = @json(old('rows', []));
</script>
<script>
    var row_index = -1;
    let rows_id = 1;
    let seqNo = 1;
    async function add_physical_inventory_lines(row={}){
        row_index = row_index+1;
        row_id = rows_id;
        // Use the current value of seqNo or the one provided in the row data
        let seqNoValue = row.seq_no ?? seqNo;

        let row_html = `
            <tr id='row_${row_id}'>

                <td>
                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
                    <input  type="text" readonly required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control small-input form-control-sm">
                </td>
                <td style="min-width:200px;">
                    <select style="width:100%;" {{$disabled}} required name="rows[${row_index}][product_id]" id="product_id_${row_index}" class="form-control">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Product::dropdown() as $product)
                            <option value="{{ $product->id }}" ${oldValues?.rows?.[row_index]?.product_id == {{ $product->id }} ? 'selected' : row?.product_id == {{ $product->id }} ? 'selected' : ''}>{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="min-width:200px;">
                    <select style="width:100%;" {{$disabled}} name="rows[${row_index}][locator_id]" id="locator_id_${row_index}" class="form-control">
                        <option value=''>--Select--</option>
                        @foreach (App\Models\Locator::locators() as $locator)
                            <option value="{{ $locator->id }}" ${oldValues?.rows?.[row_index]?.locator_id == {{ $locator->id }} ? 'selected' : row?.locator_id == {{ $locator->id }} ? 'selected' : ''}>{{ $locator->locator_type }}</option>
                        @endforeach
                    </select>
                </td>
                
                
                <td><input type="text" {{$disabled}} value="${oldValues?.rows?.[row_index]?.description ?? row?.description ?? ''}" name="rows[${row_index}][description]" id="description_${row_index}" placeholder="ex:" class="form-control small-input form-control-sm"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.system_qty ?? row?.system_qty ?? ''}" name="rows[${row_index}][system_qty]" id="system_qty_${row_index}" placeholder="00" class="form-control small-input form-control-sm"></td>
                <td><input type="number" {{$disabled}} value="${oldValues?.rows?.[row_index]?.physical_qty ?? row?.physical_qty ?? ''}" name="rows[${row_index}][physical_qty]" id="physical_qty_${row_index}" placeholder="0" class="form-control small-input form-control-sm"></td>
                <td><input type="number" readonly {{$disabled}} value="${oldValues?.rows?.[row_index]?.adjusted_qty ?? row?.adjusted_qty ?? ''}" name="rows[${row_index}][adjusted_qty]" id="adjusted_qty_${row_index}" placeholder="0" class="form-control small-input form-control-sm"></td>
                <td> 
                    <input type="hidden" value="0" id="is_active_hidden_${row_index}" name="rows[${row_index}][is_active]">
                    <input style="margin-top:10px;" type="checkbox" 
                        value="1" 
                        id="is_active_${row_index}" 
                        name="rows[${row_index}][is_active]"
                        ${oldValues?.[row_index]?.is_active == 1 || row?.is_active == 1 || oldValues?.[row_index]?.is_active === undefined ? "checked" : ""}>
                </td>
               

                <td>
                    <div class="text-danger rm-row mt-1" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea356f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>

                    </div>
                </td>
            </tr>
        `;
        $('#tableBody').append(row_html);
        // Initialize Select2 for newly added row
        $(`#product_id_${row_index}`).select2();
        $(`#locator_id_${row_index}`).select2({
            width: '100%',
        });
        // Add event listeners to update adjusted_qty
        $(`#physical_qty_${row_index}, #system_qty_${row_index}`).on('input', function() {
            let physicalQty = parseFloat($(`#physical_qty_${row_index}`).val()) || 0;
            let systemQty = parseFloat($(`#system_qty_${row_index}`).val()) || 0;
            let adjustedQty = physicalQty - systemQty;
            $(`#adjusted_qty_${row_index}`).val(adjustedQty);
        });

        rows_id++;
        seqNo++;
        // calculateTotal();
        return row_id;
        // checkSelect2Validity();

    }


    function openModal() {
        // let inventoryFromLocator = $('#locator_from').val();
        // let inventoryToLocator = $('#locator_to').val();
        // add_physical_inventory_lines({locator_from: inventoryFromLocator,locator_to:inventoryToLocator});
        add_physical_inventory_lines();
    }

    function deleteRow(row_id) {

        if (confirm("Are you sure you want to delete this row?")) {
            console.log('row id is:'+row_id);

            let structure_id = $('#row_id_' + row_id).val();

            console.log(structure_id);
            if (structure_id) {
                ffsQuiet($.post('/delete-physical_inv-row/' + structure_id, {
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

    async function load_edit(){
        @foreach ($activity->physicalInvLine as $row)
        // var activity = @json($activity);
            console.log("this is edit activity:", {!! $row !!});

            row_id = await add_physical_inventory_lines({!! $row !!});
        @endforeach
    }

    $(document).ready(async function(){
        await load_edit();
        // calculateTotal();
        if (oldValues.length > 0) {
            oldValues.forEach(row => add_physical_inventory_lines(row));
    }
        $('#warehouse_id').select2();
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
        // calculate_total();
    })
    $('#btn_complete').click(function() {
        $('#document_status_hidden').val('completed')
        // calculate_total();
    })
    // for later............

    // Open the modal when the "Document Action" button is clicked
    // $('#document_action_btn').on('click', function () {
    //         $('#documentActionModal').modal('show');
    //     });

    // // Handle "Draft" button click
    // $('#draftBtn').on('click', function () {
    //     // Set the hidden input value to "Draft"
    //     $('#document_action_input').val('draft');
    //     // Submit the form
    //     // $('#yourFormId').submit(); // Replace `yourFormId` with the actual ID of your form
    // });

    // // Handle "Complete" button click
    // $('#completeBtn').on('click', function () {
    //     // Set the hidden input value to "Complete"
    //     $('#document_action_input').val('completed');
    //     // Submit the form
    //     // $('#yourFormId').submit(); // Replace `yourFormId` with the actual ID of your form
    // });
</script>
