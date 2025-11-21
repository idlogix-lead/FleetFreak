{{-- @php
    $disabled = '';
    if ($activity->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp --}}
<style>
    .pricelist-version tbody tr td{
            padding:0px;
        }
        .pricelist-version tbody tr td:nth-child(2) {
            max-width: 200px;
            min-width: 150px;
        }
        .pricelist-version tbody tr td:nth-child(1) {
            max-width: 80px;
        }
        .pricelist-version tbody tr td input, .pricelist-version tbody tr td select{
            /* padding:0px;
            width:100%;
            height:100%; */
            border-color:transparent;
            border-radius:0px;
        }
        .pricelist-version tbody tr td input:focus,
         .pricelist-version tbody tr td select:focus{
            /* padding:0px;
            width:100%;
            height:100%; */
            box-shadow:0px 0px 0px transparent;
        }
        .rm-row svg:hover{
            color:  #fff;
            fill:  red;
            cursor: pointer;
        }
        .state-wrapper{
            font-size: 14px;
            color:black;
        }

        .error {
            border: 1px solid red !important; /* Red border for error state */
        }
</style>
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

        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="name">Name</label>
                <input  type="text" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$activity->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="currency">Currency</label>
                <input type="text" name="currency" class="form-control {{($errors->has('currency') ? ' is-invalid' : '')}}" id="currency" value="{{old('currency',$activity->currency)}}">
                {!! $errors->first('currency', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="price_precision">Price Precision</label>
                <input type="number" name="price_precision" class="form-control {{($errors->has('price_precision') ? ' is-invalid' : '')}}" id="currency" value="{{old('price_precision',$activity->price_precision)}}">
                {!! $errors->first('price_precision', '<div class="invalid-feedback">:message</div>') !!}
            </div>
       
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="description">Description</label>
                <input name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" rows="4" {{ old('description', $activity->description) }}>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="sales_price_list" id="sales_price_list_hidden">

                <input type="checkbox" value="1" name="sales_price_list" class="form-check-input" id="sales_price_list" {{ old('sales_price_list', $activity->sales_price_list) ? 'checked' : '' }}>
                <label class="form-check-label" for="sales_price_list">Sales Price List</label>
            </div>
        </div>   
        <div class="col-md-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="price_includes_tax" id="price_includes_tax_hidden">

                <input type="checkbox" value="1" name="price_includes_tax" class="form-check-input" id="price_includes_tax" {{ old('price_includes_tax', $activity->price_includes_tax) ? 'checked' : '' }}>
                <label class="form-check-label" for="price_includes_tax">Price Includes Tax</label>
            </div>
        </div>   
        <div class="col-md-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="enforce_price_limit" id="enforce_price_limit_hidden">

                <input type="checkbox" value="1" name="enforce_price_limit" class="form-check-input" id="enforce_price_limit" {{ old('enforce_price_limit', $activity->enforce_price_limit) ? 'checked' : '' }}>
                <label class="form-check-label" for="enforce_price_limit">Enforce Price Limit</label>
            </div>
        </div>   

        <div class="col-md-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($activity) && $activity->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>  
        @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\PriceList::where('company_id',auth()->user()->active_company())->where('is_active',1)->where('is_default', 1)->exists();
        @endphp

        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default"
                    {{ old('is_default', $activity->is_default) ? 'checked' : '' }}
                    @if($isActiveDisabled && $activity->is_default== 0) disabled @endif>
                <label class="form-check-label" for="is_default">Is Default</label>
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
            {{-- @if (!$disabled) --}}
            <button type="button" class="btn btn-outline-primary float-end btn-sm" onclick="openModal()" data-toggle="tooltip" data-placement="left" title="Add Row">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
            </button>
            {{-- @endif --}}
        {{-- </div> --}}
        <h4>Pricelist Version</h4>

    </div>
    <div class="table-responsive">
        <table class="table pricelist-version small">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Name <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Description <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Valid From <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Is Active <span>
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
                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
                    <input  type="text" readonly required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control">
                </td>
                <td><input type="text" required value="${oldValues?.rows?.[row_index]?.name ?? row?.name ?? ''}" name="rows[${row_index}][name]" id="name_${row_index}" class="form-control"></td>
                <td><input type="text" value="${oldValues?.rows?.[row_index]?.description ?? row?.description ?? ''}" name="rows[${row_index}][description]" id="description_${row_index}" class="form-control"></td>
                <td><input type="date" required value="${oldValues?.rows?.[row_index]?.valid_from ?? row?.valid_from ?? ''}" name="rows[${row_index}][valid_from]" id="valid_from_${row_index}" class="form-control"></td>
                <td>
                    <input type="hidden" value="0" id="is_active_hidden_${row_index}" name="rows[${row_index}][is_active]">
                    <input style="margin-top:10px;" type="checkbox"  ${(oldValues?.rows?.[row_index]?.is_active ?? 1) == 1 ? "checked" : ""} value="1" id="is_active_${row_index}" name="rows[${row_index}][is_active]">
                </td>
                <td>
                    <div class=" text-danger rm-row  float-end mt-1" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>

                    </div>
                </td>
            </tr>
        `;
        $('#tableBody').append(row_html);
        rows_id++;
        seqNo++;
        calculateTotal();
        return row_id;
        // checkSelect2Validity();

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
                $.post('/delete-version-row/' + structure_id, {
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
        @foreach ($activity->priceListVersion as $row)
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
