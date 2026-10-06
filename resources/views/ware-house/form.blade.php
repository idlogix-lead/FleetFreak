{{-- @php
    $disabled = '';
    if ($activity->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp --}}
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="company_id">Company  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$wareHouse->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$wareHouse->description)}}" autofocus>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" placeholder="Code" name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{old('code',$wareHouse->code)}}" autofocus>
                {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" placeholder="Address" name="address" class="form-control {{($errors->has('address') ? ' is-invalid' : '')}}" id="address" value="{{old('address',$wareHouse->address)}}" autofocus>
                {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="source_warehouse_id">Source WareHouse</label>
                <select name="source_warehouse_id" class="form-control {{ $errors->has('source_warehouse_id') ? ' is-invalid' : '' }}" id="source_warehouse_id" autofocus>
                    <option value="">Select a Source Warehouse</option>
                    @foreach(\App\Models\WareHouse::warehouses() as $source_warehouse)
                        <option value="{{ $source_warehouse->id }}" {{ $wareHouse->source_warehouse_id == $source_warehouse->id ? 'selected' : '' }}>
                            {{ $source_warehouse->name }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('source_warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="locator_id">Locators</label>
                <select name="locator_id" class="form-control {{ $errors->has('locator_id') ? ' is-invalid' : '' }}" id="locator_id">
                    <option value="">Select a Locator</option>
                    @foreach(\App\Models\Locator::all_locators() as $locators)
                        <option value="{{ $locators->id }}" {{ $wareHouse->locator_id == $locators->id ? 'selected' : '' }}>
                            {{ $locators->locator_type }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('locator_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($wareHouse) && $wareHouse->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="in_transit" id="in_transit_hidden">

                <input type="checkbox" value="1" name="in_transit" class="form-check-input" id="in_transit" {{ old('in_transit', $wareHouse->in_transit) ? 'checked' : '' }}>
                <label class="form-check-label" for="in_transit">In Transit</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_disallow_negative_inv" id="is_disallow_negative_inv_hidden">

                <input type="checkbox" value="1" name="is_disallow_negative_inv" class="form-check-input" id="is_disallow_negative_inv" {{ old('is_disallow_negative_inv', $wareHouse->is_disallow_negative_inv) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_disallow_negative_inv">Is Disallow Negative Inv</label>
            </div>
        </div>
        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default" {{ old('is_default', $wareHouse->is_default) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div>



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
            {{-- @if (!$disabled) --}}
            <button type="button" class="btn btn-outline-primary float-end" onclick="openModal()" data-toggle="tooltip" data-placement="left" title="Add Row">
                <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </button>
            {{-- @endif --}}
        {{-- </div> --}}
        <h2>Locators</h2>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered ">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Code <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Locator Type <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Relative Priority <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Aisle (X) <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Bin (Y) <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Level (Z) <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Is Active <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Is Default <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                
                    <th>Actions <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
              
                </tr>
            </thead>
            <tbody id="tableBody">

            </tbody>
        </table>
    </div>
</div>
<div class="container">
    {{-- @if (!$disabled) --}}
        @php
            // $model = [
            //     'notify_btn' => 'Save as Draft',
            //     'function' => 'Save',
            //     'body' => 'Please Confirm do you realy want to Draft?',
            //     'btn-color' => 'primary',
            //     'float' => 'end mt-2',
            //     'id' => 'draft',
            // ];

            $model2 = [
                'notify_btn' => 'Save',
                'function' => 'Save',
                'body' => 'Please Confirm do you realy want to Save?',
                'btn-color' => 'success',
                'float' => 'end mt-2',
                'id' => 'save',
            ];
        @endphp
        {{-- @include('partials.modal', ['data' => $model]) --}}
        @include('partials.modal', ['data' => $model2])
    {{-- @endif --}}
</div>
<script>
    let oldRows = @json(old('rows', [])); // Convert Laravel's old values to JSON
</script>

<script>
    var row_index = -1;
    let rows_id = 1;
    let seqNo = 1;
    // async function add_po_line_row(row={}){
    //     row_index = row_index+1;
    //     row_id = rows_id;
    //     // Use the current value of seqNo or the one provided in the row data
    //     let seqNoValue = row.seq_no ?? seqNo;

    //     let row_html = `
    //         <tr id='row_${row_id}'>

    //             <td>

    //                 <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
    //                 <input type="text" required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control"></td>
    //             <td><input  type="text" required value="${row.code??""}" name="rows[${row_index}][code]" id="code_${row_index}" class="form-control"></td>
    //             <td><input  type="text" required value="${row.locator_type??""}" name="rows[${row_index}][locator_type]" id="locator_type_${row_index}" class="form-control"></td>
    //             <td><input  type="text" value="${row.relative_priority??""}" name="rows[${row_index}][relative_priority]" id="relative_priority_${row_index}" class="form-control"></td>
    //             <td><input  type="number" value="${row.aisle??""}" name="rows[${row_index}][aisle]" id="aisle_${row_index}" class="form-control"></td>
    //             <td><input  type="number" value="${row.bin??""}" name="rows[${row_index}][bin]" id="bin_${row_index}" class="form-control"></td>
    //             <td><input  type="number" value="${row.level??""}" name="rows[${row_index}][level]" id="level_${row_index}" class="form-control"></td>
    //             <td> <input type="hidden" value="0"  id="is_active_hidden_${row_index}"  name="rows[${row_index}][is_active]" >
    //                     <input style="margin-top:10px;" type="checkbox" checked ${row.is_active == 1?"checked":""} value="1"  id="is_active_${row_index}"  name=rows[${row_index}][is_active]" >
    //             </td>  
    //              <td> <input type="hidden" value="0"  id="is_default_hidden_${row_index}"  name="rows[${row_index}][is_default]" >
    //                     <input style="margin-top:10px;" type="checkbox" checked ${row.is_default == 1?"checked":""} value="1"  id="is_default_${row_index}"  name=rows[${row_index}][is_default]" >
    //             </td>  
    //             <td>
    //                 <button type="button" class="btn btn-outline-danger  float-end" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
    //                     <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
    //                         <polyline points="3 6 5 6 21 6"></polyline>
    //                         <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
    //                         <line x1="10" y1="11" x2="10" y2="17"></line>
    //                         <line x1="14" y1="11" x2="14" y2="17"></line>
    //                     </svg>
    //                 </button>
    //             </td>
    //         </tr>
    //     `;
    //     $('#tableBody').append(row_html);
    //     rows_id++;
    //     seqNo++;
    //     calculateTotal();
    //     return row_id;
    //     // checkSelect2Validity();

    // }
    async function add_po_line_row(row = {}) {
        row_index = row_index + 1;
        row_id = rows_id;

        // Check if old data exists for this row
        let oldRow = oldRows[row_index] ?? {};

        // Use old value if available, otherwise use provided row data or default
        let seqNoValue = oldRow.seq_no ?? row.seq_no ?? seqNo;
        let codeValue = oldRow.code ?? row.code ?? "";
        let locatorTypeValue = oldRow.locator_type ?? row.locator_type ?? "";
        let relativePriorityValue = oldRow.relative_priority ?? row.relative_priority ?? "";
        let aisleValue = oldRow.aisle ?? row.aisle ?? "";
        let binValue = oldRow.bin ?? row.bin ?? "";
        let levelValue = oldRow.level ?? row.level ?? "";
        let isActiveValue = oldRow.is_active ?? row.is_active ?? "";
        let isDefaultValue = oldRow.is_default ?? row.is_default ?? 0;

        let row_html = `
            <tr id='row_${row_id}'>
                <td>
                    <input type="hidden" id="row_id_${row_id}" value="${row.id ?? ""}" name="rows[${row_index}][row_id]">
                    <input type="text" required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control">
                </td>
                <td><input type="text" required value="${codeValue}" name="rows[${row_index}][code]" id="code_${row_index}" class="form-control"></td>
                <td><input type="text" required value="${locatorTypeValue}" name="rows[${row_index}][locator_type]" id="locator_type_${row_index}" class="form-control"></td>
                <td><input type="text" value="${relativePriorityValue}" name="rows[${row_index}][relative_priority]" id="relative_priority_${row_index}" class="form-control"></td>
                <td><input type="number" value="${aisleValue}" name="rows[${row_index}][aisle]" id="aisle_${row_index}" class="form-control"></td>
                <td><input type="number" value="${binValue}" name="rows[${row_index}][bin]" id="bin_${row_index}" class="form-control"></td>
                <td><input type="number" value="${levelValue}" name="rows[${row_index}][level]" id="level_${row_index}" class="form-control"></td>
                <td>
                    <input type="hidden" value="0" id="is_active_hidden_${row_index}" name="rows[${row_index}][is_active]">
                    <input style="margin-top:10px;" type="checkbox" ${isActiveValue == 1 ? "checked" : ""} value="1" id="is_active_${row_index}" name="rows[${row_index}][is_active]">
                </td>
                <td>
                    <input type="hidden" value="0" id="is_default_hidden_${row_index}" name="rows[${row_index}][is_default]">
                    <input style="margin-top:10px;" type="checkbox" ${isDefaultValue == 1 ? "checked" : ""} value="1" id="is_default_${row_index}" name="rows[${row_index}][is_default]">
                </td>
                <td>
                    <button type="button" class="btn btn-outline-danger float-end" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </td>
            </tr>
        `;

        $('#tableBody').append(row_html);
        rows_id++;
        seqNo++;

        calculateTotal();
        return row_id;
    }


    function openModal() {
        add_po_line_row();
    }

    function deleteRow(row_id) {

        if (confirm("Are you sure you want to delete this row?")) {
            console.log('row id is:'+row_id);

            let structure_id = $('#row_id_' + row_id).val();

            console.log(structure_id);
            if (structure_id) {
                ffsQuiet($.post('/delete-poline-row/' + structure_id, {
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
        @foreach ($wareHouse->locators as $row)
            console.log("this is edit activity:", {!! $row !!});
            row_id = await add_po_line_row({!! $row !!});
        @endforeach
    }

    $(document).ready(async function(){
        await load_edit();
        calculateTotal();
        if (oldRows.length > 0) {
        oldRows.forEach(row => add_po_line_row(row));
    }
        $('#business_partner_id').select2();
        $('#invoice_partner_id').select2();
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
    function calculateTotal() {
        let total = 0;

        // Loop through each row
        $('.rate-input').each(function () {
            let row = $(this).closest('tr'); // Get the current row
            let rate = parseFloat($(this).val()) || 0; // Get the rate value
            let quantity = parseFloat(row.find('input[name*="[quantity]"]').val()) || 0; // Get the quantity value
            let taxPercentage = parseFloat(row.find('input[name*="[tax]"]').val()) || 0; // Get the tax percentage value
            let discount = parseFloat(row.find('input[name*="[discount]"]').val()) || 0; // Get the discount value

            // Calculate subtotal (rate × quantity)
            let subtotal = rate * quantity;

            // Calculate tax amount (taxPercentage% of subtotal)
            let taxAmount = (subtotal * taxPercentage) / 100;

            // Calculate line amount (subtotal + tax - discount)
            let lineAmount = subtotal + taxAmount - discount;
            row.find('input[name*="[line_amount]"]').val(lineAmount.toFixed(2)); // Update line amount field

            // Calculate total line amount (line amount + tax amount)
            let totalLineAmount = lineAmount + taxAmount;
            row.find('input[name*="[total_line_amount]"]').val(totalLineAmount.toFixed(2)); // Update total line amount field

            // Add to the total booking amount
            if (!isNaN(totalLineAmount)) {
                total += totalLineAmount;
            }
        });

        // Update the total booking amount
        $('#booking_amount').val(total.toFixed(2));
    }

    // Attach the calculateTotal function to relevant input fields
    $('body').on('input', '.rate-input, input[name*="[quantity]"], input[name*="[tax]"], input[name*="[discount]"], input[name*="[tax_value]"]', function () {
        calculateTotal();
    });

</script>
{{-- @dd($activity->activityLines) --}}
