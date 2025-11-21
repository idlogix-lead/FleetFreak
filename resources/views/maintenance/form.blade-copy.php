@php
    $disabled = '';
    if ($maintenance->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="company_id">Company  </label>
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
                                {{ !isset($edit)?($_GET['vehicle_id']??null == $vehicle->id? 'selected':''):($vehicle->id == $maintenance->vehicle_id ? 'selected' : '') }}>
                                {{ $vehicle->vehicleModel->name }} {{ $vehicle->registration_no }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" placeholder="Vehicle Id" name="vehicle_id" class="form-control {{($errors->has('vehicle_id') ? ' is-invalid' : '')}}" id="vehicle_id" value="{{$maintenance->vehicle_id}}"> --}}
                    {!! $errors->first('vehicle_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="business_partner_id">Business Partner</label>
                    <select {{ $disabled }} name="business_partner_id" autofocus required id="business_partner_id"
                        class="form-control {{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select --</option>
                        @foreach (App\Models\Partner::BusinessPartnerDropdownSimple() as $business_partner)
                            <option value="{{ $business_partner->id }}"
                                {{ $maintenance->business_partner_id == $business_partner->id ? 'selected' : '' }}>
                                {{ Str::title($business_partner->name) }}</option>
                        @endforeach
                    </select>
                    {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                    {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input {{ $disabled }} type="date" autofocus required name="date"
                        class="form-control {{ $errors->has('date') ? ' is-invalid' : '' }}" id="date"
                        value="{{ $maintenance->date }}">
                    {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <input type="text" disabled placeholder="Document Type" name="document_type" class="form-control {{($errors->has('document_type') ? ' is-invalid' : '')}}" id="document_type" value="{{ucfirst($maintenance->document_type)}}" >
                    {!! $errors->first('document_type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <label for="document_type_id">Document Type</label>
                    <select disabled name='document_type_id' id='document_type_id' class='form-control {{($errors->has('document_type_id') ? ' is-invalid' : '')}}' autofocus required >
                        <option value = "">-- Select -- </option>

                        @foreach (App\Models\InvoiceDocumentType::documentTypes() as $type)
                            <option value='{{$type->id}}'  {{$type->id == $maintenance->document_type_id  ? 'selected':''}} >{{Str::title($type->name)}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('document_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_type_id">Document Type</label>
                    <input type="text" disabled placeholder="Document Type" name="document_type_id"
                        class="form-control {{ $errors->has('document_type_id') ? ' is-invalid' : '' }}"
                        id="document_type_id" value="{{ isset($maintenance->InvDocumentType->name) ?ucfirst($maintenance->InvDocumentType->name): ($is_inspection?'Inspection':'Maintainence') }}">
                    {!! $errors->first('document_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_no">Document No</label>
                    <input type="text" disabled placeholder="Document No" name="document_no"
                        class="form-control {{ $errors->has('document_no') ? ' is-invalid' : '' }}" id="document_no"
                        value="{{ $maintenance->document_no }}">
                    {!! $errors->first('document_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="form-group">
                    <label for="company_id">Company Id</label>
                    <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$maintenance->company_id}}">
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-md-3">
                <div class="form-group">
                    <label for="total_amount">Total Amount</label>
                    <input type="text" {{ $disabled }} autofocus required readonly placeholder="Total Amount"
                        name="total_amount"
                        class="form-control {{ $errors->has('total_amount') ? ' is-invalid' : '' }}"
                        id="total_amount" value="{{ $maintenance->total_amount ?? 0 }}">
                    {!! $errors->first('total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="grand_total_amount">Grand Total Amount</label>
                    <input type="text" {{ $disabled }} autofocus required readonly
                        placeholder="Grand Total Amount" name="grand_total_amount"
                        class="form-control {{ $errors->has('grand_total_amount') ? ' is-invalid' : '' }}"
                        id="grand_total_amount" value="{{ $maintenance->grand_total_amount ?? 0 }}">
                    {!! $errors->first('grand_total_amount', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="document_status">Document Status</label>
                    <input type="hidden" name="document_status" id="document_status_hidden"
                        value="{{ $maintenance->document_status ? $maintenance->document_status : 'draft' }}">
                    <input type="text" {{ $disabled }} autofocus required readonly placeholder="Document Status"
                        class="form-control {{ $errors->has('document_status') ? ' is-invalid' : '' }}"
                        id="document_status"
                        value="{{ $maintenance->document_status ? ucfirst($maintenance->document_status) : 'Draft' }}">
                    {!! $errors->first('document_status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" {{ $disabled }} placeholder="Description" name="description"
                        class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description"
                        value="{{ $maintenance->description }}">
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
                                <th>Is Checked</th>
                                <th>Activity</th>
                                <th>Product</th>
                                <th>Description</th>
                                <th>Quantity</th>
                                <th>Rate</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="MaintenanceLineBody"></tbody>
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
        // console.log(jQuery.isEmptyObject(line));

        let table_row = `
            <tr id='row_${row_index}'>
                <td style='max-width:100px;'>
                    <input type='number' placeholder='#' disabled value="${row_no}" name="row[${row_index}][no]" id="row_no_${row_index}" class="form-control">
                    <input type='hidden' value="${row.id??''}" name="row[${row_index}][row_id]" id="row_row_id_${row_index}">
                </td>`;


        if (!jQuery.isEmptyObject(line) && !row.is_service_charge) {
            table_row += `<td>
                        <input type='hidden' value="0" name="row[${row_index}][is_checked]" id="row_is_checked_hidden_${row_index}">
                        <input type='checkbox' {{ $disabled }} ${row.is_checked == 1? 'checked':''}  value="1" name="row[${row_index}][is_checked]" id="row_is_checked_${row_index}">
                    </td>`;
            table_row += `<td>
                        <input type='hidden' value="${line.id}" name="row[${row_index}][activity_id]" id="row_activity_id_hidden_${row_index}">
                        <input type='text' disabled value="${line.name}" id="row_activity_id_${row_index}" class="form-control ">
                    </td>`;
        } else {
            table_row += `<td>
                        {{-- is_checked --}}
                    </td>`;
            table_row += `<td>
                        <input type='hidden' value="" name="row[${row_index}][activity_id]" id="row_activity_id_${row_index}">
                    </td>`
        }


        if (row.is_service_charge) {
            table_row +=
                `<td>
                        {{-- product --}}
                    </td>
                    <td>
                        <input type='hidden' value="1" name="row[${row_index}][is_service_charge]" id="row_is_service_charge_${row_index}">
                        <input type='text' {{ $disabled }} readonly placeholder='Details' value="Service Charges" name="row[${row_index}][description]" id="row_description_${row_index}" class="form-control">
                    </td>`;
        } else {
            if (jQuery.isEmptyObject(line)) {
                table_row += `<td style="min-width:200px;">
                            <select {{ $disabled }}  onchange='calculate_total()' style="width:100%;" name="row[${row_index}][product_id]" id="row_product_id_${row_index}" class="form-control">
                                <option value=''>--Select--</option>
                                @foreach (App\Models\Product::dropdown() as $product)
                                    <option value="{{ $product->id }}" ${select_products(row.invoice_line_products, {{ $product->id }})?'selected':''} >{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </td>`;
            } else {
                table_row +=
                    `<td>
                            {{-- product --}}
                        </td>`;
            }
            table_row += `<td>
                        <input type='hidden' value="0" name="row[${row_index}][is_service_charge]" id="row_is_service_charge_${row_index}">
                        <input type='text' onchange='calculate_total()' {{ $disabled }} placeholder='Details' ${jQuery.isEmptyObject(line)?'required':''} value="${row.description??''}" name="row[${row_index}][description]" id="row_description_${row_index}" class="form-control">
                    </td>`;
        }
        if (row.is_service_charge) {
            table_row += `<td>
                        <input type='number' {{ $disabled }} autofocus required value="1" readonly placeholder='0' name="row[${row_index}][quantity]" id="row_quantity_${row_index}" class="form-control qty">
                    </td>`;
        } else {
            table_row += `<td>
                        <input type='number' {{ $disabled }} autofocus required oninput="calculate_line_total(${row_index})" value="${row.quantity??0}" placeholder='0' name="row[${row_index}][quantity]" id="row_quantity_${row_index}" class="form-control qty" min='0'>
                    </td>`;
        }
        table_row +=
            `<td>
                    <input type='number' {{ $disabled }} autofocus required required value="${row.rate??0}" oninput="calculate_line_total(${row_index})" placeholder='0' name="row[${row_index}][rate]" id="row_rate_${row_index}" row_index="${row_index}" min="0" class="form-control rate">
                </td>
                <td>
                    <input type='number' {{ $disabled }} autofocus required readonly value="${(row.quantity*row.rate)??0}" readonly placeholder='0' name="row[${row_index}][line_total]" id="row_line_total_${row_index}" class="form-control">
                </td>`;
        if (jQuery.isEmptyObject(line) && !row.is_service_charge && "{{ $disabled }}" != 'disabled') {
            table_row +=
                `<td>
                        <div class="btn btn-sm btn-outline-danger" onclick='delete_row(${row_index}, ${row.id??null})'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        </div>
                    </td>`;
        } else {

            table_row +=
                `<td>

                    </td>`;
        }
        table_row +=
            `</tr>
        `;
        $("#MaintenanceLineBody").append(table_row);
        $(`#row_product_id_${row_index}`).select2({
            width: '100%',
            multiple: false
        });
        // $(`#row_activity_id_${row_index}`).select2({ width: '100%' });
    }
    async function delete_row(row_index_ref, row_id) {
        if (confirm('Are you sure you want to remove Row no ' + (row_index_ref + 1))) {
            if (row_id) {
                try {
                    loader();
                    response = await $.post(get_host() + '{{$is_inspection?"/inspections":"/maintenances"}}' + '/destroy/row/' + row_id, {
                        _token: "{{ csrf_token() }}",
                    });

                    $('#row_' + row_index_ref).remove();
                    endloader();
                    msgboxbox.show(`Row ${row_index_ref+1} Removed Successfully!`, 'success', null);
                } catch (error) {
                    endloader();
                    msgboxbox.show(error.responseJSON.error, 'error', null);
                    // console.error("An error occurred:", error.responseJSON.message);
                }
            } else {
                $('#row_' + row_index_ref).remove();
                msgboxbox.show(`Row ${row_index_ref+1} Removed Successfully!`, 'success', null);
            }
        }
    }

    function calculate_line_total(row_index_ref) {
        // let qty = $('#row_quantity_'+row_index_ref).val();
        // let rate = $('#row_rate_'+row_index_ref).val();
        // let total = parseFloat(qty) * parseFloat(rate);
        // $('#row_line_total_'+row_index_ref).val(total);
        calculate_total();
    }

    function calculate_total() {
        // let qty = $('#row_quantity_'+row_index_ref).val();
        // let rate = $('#row_rate_'+row_index_ref).val();
        // let total = parseFloat(qty) * parseFloat(rate);
        // $('#row_line_total_'+row_index_ref).val(total);
        let total = 0
        $('.rate').each(function() {
            row_index_ref = $(this).attr('row_index');
            let qty = $('#row_quantity_' + row_index_ref).val();
            let rate = $('#row_rate_' + row_index_ref).val();
            let line_total = parseFloat(qty) * parseFloat(rate);
            $('#row_line_total_' + row_index_ref).val(line_total);


            let is_activity = $('#row_activity_id_' + row_index_ref).val();
            if (is_activity.length) {
                let description = $('#row_description_' + row_index_ref).val();
                let products = $('#row_product_id_' + row_index_ref).val() ?? 0;

                let check = (description.length??0) + parseInt(line_total??0) + parseInt(qty??0) + parseInt(rate??0) + (products.length??0);
                let check2 = (description.length??0) + parseInt(line_total??0)  + parseInt(rate??0) + (products.length??0);
                if(check2 && parseInt(qty??0) == 0){
                    $('#row_quantity_' + row_index_ref).val(1);
                }
                if (check != 0) {
                    $('#row_is_checked_' + row_index_ref).prop('checked', true);
                } else {
                    $('#row_is_checked_' + row_index_ref).prop('checked', false);
                }
            }



            if (line_total) {
                total += line_total;
            }
        });
        $('#total_amount').val(total);
        $('#grand_total_amount').val(total);

    }

    function load_activity() {
        @foreach ($activity->activityLines ?? [] as $line)
            add_row({}, {!! $line !!});
        @endforeach
    }

    function load_edit() {
        @foreach ($maintenance->invoiceLines ?? [] as $row)
            add_row({!! $row !!}, {!! $row->activityLine ?? '' !!});
        @endforeach
    }

    function add_service_charges_row() {
        add_row({
            is_service_charge: 1
        }, {});
    }
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
            @if($is_inspection)
                load_activity();
            @endif
            add_service_charges_row();
        @endif
    });
</script>
