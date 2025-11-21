@php
    $disabled = '';
    if ($tolltax->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="company_id">Company</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                    <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="vehicle_id">Vehicle</label>
                    <select name='vehicle_id' {{ $disabled }} id='vehicle_id'
                        class='form-control {{ $errors->has('vehicle_id') ? ' is-invalid' : '' }}' autofocus required>
                        <option value = "">-- Select -- </option>
                        @foreach (App\Models\Vehicle::VehicleDropdown() as $vehicle)
                            <option value='{{ $vehicle->id }}'
                                {{ $vehicle->id == $tolltax->vehicle_id ? 'selected' : '' }}>
                                {{ $vehicle->vehicleModel->name }} {{ $vehicle->registration_no }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" placeholder="Vehicle Id" name="vehicle_id" class="form-control {{($errors->has('vehicle_id') ? ' is-invalid' : '')}}" id="vehicle_id" value="{{$tolltax->vehicle_id}}"> --}}
                    {!! $errors->first('vehicle_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="business_partner_id">Business Partner</label>
                    <select {{ $disabled }} name="business_partner_id" autofocus required id="business_partner_id"
                        class="form-control {{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select --</option>
                        @foreach (App\Models\Partner::BusinessPartnerDropdownIncludeDriver() as $business_partner)
                            <option value="{{ $business_partner->id }}"
                                {{ $tolltax->business_partner_id == $business_partner->id ? 'selected' : '' }}>
                                {{ Str::title($business_partner->name) }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$tolltax->business_partner_id}}"> --}}
                    {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input {{ $disabled }} type="date" autofocus required name="date"
                        class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}" id="date"
                        value="{{ $tolltax->date }}">
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_type_id">Document Type</label>
                    <input type="text" disabled placeholder="Document Type" name="document_type_id"
                        class="form-control {{ $errors->has('document_type_id') ? ' is-invalid' : '' }}"
                        id="document_type_id" value="{{ $tolltax->InvDocumentType->name ?? 'TollTax' }}">
                    {!! $errors->first('document_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_no">Document No</label>
                    <input type="text" disabled placeholder="Document No" name="document_no"
                        class="form-control {{ $errors->has('document_no') ? ' is-invalid' : '' }}" id="document_no"
                        value="{{ $tolltax->document_no }}">
                    {!! $errors->first('document_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_amount">Total Amount</label>
                    <input type="text" {{ $disabled }} autofocus required readonly placeholder="Total Amount"
                        name="total_amount"
                        class="form-control {{ $errors->has('total_amount') ? ' is-invalid' : '' }}" id="total_amount"
                        value="{{ $tolltax->total_amount ?? 0 }}">
                    {!! $errors->first('total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="grand_total_amount">Grand Total Amount</label>
                    <input type="text" {{ $disabled }} autofocus required readonly
                        placeholder="Grand Total Amount" name="grand_total_amount"
                        class="form-control {{ $errors->has('grand_total_amount') ? ' is-invalid' : '' }}"
                        id="grand_total_amount" value="{{ $tolltax->grand_total_amount ?? 0 }}">
                    {!! $errors->first('grand_total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_status">Document Status</label>
                    <input type="hidden" name="document_status" id="document_status_hidden"
                        value="{{ $tolltax->document_status ? $tolltax->document_status : 'draft' }}">
                    <input type="text" {{ $disabled }} autofocus required readonly placeholder="Document Status"
                        class="form-control {{ $errors->has('document_status') ? ' is-invalid' : '' }}"
                        id="document_status"
                        value="{{ $tolltax->document_status ? ucfirst($tolltax->document_status) : 'Draft' }}">
                    {!! $errors->first('document_status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" {{ $disabled }} placeholder="Description" name="description"
                        class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description"
                        value="{{ $tolltax->description }}">
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
        <br>


        <div class="row">
            @if (!$disabled)
                <div class="col-md-12">
                    <div class="btn btn-outline-primary float-end btn-sm" onclick="add_row()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-plus-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Toll Amount </th>
                                <th>Check Point</th>
                                <th>Toll Tax Image</th>
                                <th>Action</th>

                                {{-- <th>Description</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Total</th> --}}
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody id="TollTaxLineBody"></tbody>

                    </table>
                </div>
            </div>
        </div>



    </div>
    <div class="box-footer mt20">
        @if (!$disabled)
            @php
                $model = [
                    'notify_btn' => 'Save as Draft',
                    'function' => 'Save',
                    'body' => 'Please Confirm do you realy want to Save?',
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
        @endif
    </div>
</div>


<script>
    row_index = -1;
    row_no = 0;
    //   let tolltax = @json($tolltax);
    function select_products(products, product_id) {
        try {
            let result = products.find(product => product.product_id === product_id);
            return !jQuery.isEmptyObject(result);
        } catch (e) {
            return false;
        }
    }

    function add_row(row = {}, line = {}) {

        row_index++;
        row_no++;
        //    <td style='max-width:100px;'>
        //             <input type='number' placeholder='#' disabled value="${row_no}" name="row[${row_index}][no]" id="row_no_${row_index}" class="form-control">
        //             <input type='hidden' value="${row.id??''}" name="row[${row_index}][row_id]" id="row_row_id_${row_index}">
        //         </td>
        // console.log(jQuery.isEmptyObject(line));

        let table_row = `
            <tr id='row_${row_index}'>
            <td style='max-width:100px;'>
                     <input type='number' placeholder='#' disabled value="${row_no}" name="row[${row_index}][no]" id="row_no_${row_index}" class="form-control">
                     <input type='hidden' value="${row.id??''}" name="row[${row_index}][row_id]" id="row_row_id_${row_index}">
             </td>
                <td>
                        <input type='number' {{ $disabled }}  placeholder='Toll-Amount' required oninput="updateTotals()" value="${row.line_amount??''}"  name="row[${row_index}][line_amount]" id="row_line_amount_${row_index}" class="form-control line_amount">
                    </td>
                 <td>
                        <input type='text'  {{ $disabled }} placeholder='Check-Point' required  value="${row.check_point??''}"  name="row[${row_index}][check_point]" id="row_check_point_${row_index}" class="form-control">
                    </td>
                 <td>
                 <div class="d-flex align-items-center">
                    <input type='file' {{ $disabled }} placeholder='Toll-Tax-Image'
                        name="row[${row_index}][picture_of_toll_tax]"
                        id="row_picture_of_toll_tax_${row_index}"
                        class="form-control">
                    <input type="hidden" name="row[${row_index}][picture_of_toll_tax_existing]" value="${row.picture_of_toll_tax ?? ''}">

                 ${row.picture_of_toll_tax
                    ? `<a href="/storage/${row.picture_of_toll_tax}" target="_blank">
                        <img src="/storage/${row.picture_of_toll_tax}" alt="Toll Tax Image"
                                style="width: 50px; height: 50px; object-fit: cover; margin-left: 10px;">
                    </a>`
                    : ''}

                 </div>

                 </td>
                 <td>
                        <div class="btn btn-sm btn-outline-danger" onclick='delete_row(${row_index}, ${row.id??null})'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </div>
                    </td>


                 `;


        table_row +=
            `</tr>
        `;
        $("#TollTaxLineBody").append(table_row);
        updateTotals();
        // $(`#row_product_id_${row_index}`).select2({
        //     width: '100%',
        //     multiple: false
        // });
        // $(`#row_activity_id_${row_index}`).select2({ width: '100%' });
    }

    // function updateTotals(input) {
    //     let totalAmount = 0;

    //     // Loop through all elements with the class 'toll-amount'
    //     document.querySelectorAll('.line-amount').forEach(input => {
    //         const value = parseFloat(input.value) || 0; // Parse the value as a number, default to 0 if empty
    //         totalAmount += value;
    //     });

    //     // Update the Total Amount field
    //     document.getElementById('total_amount').value = totalAmount.toFixed(2);

    //     // Update the Grand Total field (you can add logic for additional calculations here)
    //     document.getElementById('grand_total_amount').value = totalAmount.toFixed(2);
    //     // const tollAmount = parseFloat(input.value) || 0; // Get the value from the input, default to 0 if empty

    //     // // Update the Total Amount field
    //     // document.getElementById('total_amount').value = tollAmount.toFixed(2);

    //     // // Update the Grand Total field
    //     // document.getElementById('grand_total_amount').value = tollAmount.toFixed(2);
    // }
    function updateTotals() {
    let totalAmount = 0;

    // Iterate over all inputs with the class `line_amount`
    $('.line_amount').each(function () {
        const value = parseFloat($(this).val()) || 0; // Default to 0 if empty
        totalAmount += value;
    });

    // Update the Total Amount field
    $('#total_amount').val(totalAmount.toFixed(2));

    // Update the Grand Total field
    $('#grand_total_amount').val(totalAmount.toFixed(2));
    }


    async function delete_row(row_index_ref, row_id) {
        if (confirm('Are you sure you want to remove Row no ' + (row_index_ref + 1))) {
            if (row_id) {
                try {
                    loader();
                    response = await $.post(get_host() + '/toll-taxes/destroy/row/' + row_id, {
                        _token: "{{ csrf_token() }}",
                    });

                    $('#row_' + row_index_ref).remove();
                    updateTotals();
                    endloader();
                    msgboxbox.show(`Row ${row_index_ref+1} Removed Successfully!`, 'success', null);
                } catch (error) {
                    endloader();
                    msgboxbox.show(error.responseJSON.error, 'error', null);
                    // console.error("An error occurred:", error.responseJSON.message);
                }
            } else {
                $('#row_' + row_index_ref).remove();
                updateTotals();
                msgboxbox.show(`Row ${row_index_ref+1} Removed Successfully!`, 'success', null);
            }
        }
    }

    // function calculate_line_total(row_index_ref) {
    //     // let qty = $('#row_quantity_'+row_index_ref).val();
    //     // let rate = $('#row_rate_'+row_index_ref).val();
    //     // let total = parseFloat(qty) * parseFloat(rate);
    //     // $('#row_line_total_'+row_index_ref).val(total);
    //     calculate_total();
    // }

    // function calculate_total() {
    //     // let qty = $('#row_quantity_'+row_index_ref).val();
    //     // let rate = $('#row_rate_'+row_index_ref).val();
    //     // let total = parseFloat(qty) * parseFloat(rate);
    //     // $('#row_line_total_'+row_index_ref).val(total);
    //     let total = 0
    //     $('.rate').each(function() {
    //         row_index_ref = $(this).attr('row_index');
    //         let qty = $('#row_quantity_' + row_index_ref).val();
    //         let rate = $('#row_rate_' + row_index_ref).val();
    //         let line_total = parseFloat(qty) * parseFloat(rate);
    //         $('#row_line_total_' + row_index_ref).val(line_total);


    //         let is_activity = $('#row_activity_id_' + row_index_ref).val();
    //         if (is_activity.length) {
    //             let description = $('#row_description_' + row_index_ref).val();
    //             let products = $('#row_product_id_' + row_index_ref).val() ?? 0;
    //             let check = description.length + line_total + qty + rate + products.length;
    //             if (check != 0) {
    //                 $('#row_is_checked_' + row_index_ref).prop('checked', true);
    //             } else {
    //                 $('#row_is_checked_' + row_index_ref).prop('checked', false);
    //             }
    //         }



    //         if (line_total) {
    //             total += line_total;
    //         }
    //     });
    //     $('#total_amount').val(total);
    //     $('#grand_total_amount').val(total);

    // }

    function load_activity() {
        @foreach ($activity->activityLines ?? [] as $line)
            add_row({}, {!! $line !!});
        @endforeach
    }

    function load_edit() {
        @foreach ($tolltax->invoiceLines ?? [] as $row)
            add_row({!! $row !!}, {!! $row->activityLine ?? '' !!});
        @endforeach
    }

    // function add_service_charges_row() {
    //     add_row({
    //         is_service_charge: 1
    //     }, {});
    // }
    $('#btn_draft').click(function() {
        $('#document_status_hidden').val('draft')
        calculate_total()
    })
    $('#btn_complete').click(function() {
        $('#document_status_hidden').val('completed')
        calculate_total()
    })
    $(document).ready(function() {
        @if (isset($edit))
            load_edit();
        @else
            load_activity();
            // add_service_charges_row();
            add_row()
        @endif
    });
</script>
