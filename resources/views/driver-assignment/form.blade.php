<style>

    table > tbody > tr > td {
        padding: 0px !important;
        text-align: center;
        
        /* background-color: #00000043;   */
    }
    table > tbody > tr > td:focus-within {
        /* Styles for the parent element when a child has focus */
        border: 2px solid rgb(20, 149, 218);  /* Example: add a blue border */
        /* background-color: #eee;  */
    }
    table > tbody > tr > td input,
    table > tbody > tr > td select,
    table > tbody > tr > td textarea
    {
        /* color:green; */
        /* padding: 0px !important; */
        margin: 0px !important;
        border-color: transparent !important;
        border-radius: 0px !important;

    }
    table > tbody > tr > td .btn {
        /* background-color:red !important; */
        border-color:transparent !important;
       
        

    }
</style>
   
{{-- <div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="order_no">Order No</label>
                <input type="text" readonly placeholder="Order No" name="order_no" class="form-control {{($errors->has('order_no') ? ' is-invalid' : '')}}" id="order_no" value="{{isset($nextOrderNo) ? $nextOrderNo : $order->order_no}}">
                {!! $errors->first('order_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="business_partner_order_id">Business Partner</label>
                <select name="business_partner_id" id="business_partner_order_id" onchange='hide_customer()' class="form-control{{ $errors->has('business_partner_order_id') ? ' is-invalid' : '' }}" disabled >
                    <option value="">-- Select --</option>
                    @php
                        $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                        $count = $businessPartners->count();
                    @endphp
                    @foreach($businessPartners as $business_partner)
                        <option value="{{ $business_partner->id }}" 
                            {{ $order->business_partner_id == $business_partner->id ? 'selected' : '' }}
                        >{{ Str::title($business_partner->company_name) }}</option>
                    @endforeach
                    @if($count == 1)
                        <script>
                            // Automatically select the only option if there's only one business partner
                            document.getElementById('business_partner_order_id').selectedIndex = 1;
                        </script>
                    @endif
                </select>
                <input type="hidden" name="business_partner_id" value="{{ $order->business_partner_id }}">

                {!! $errors->first('business_partner_order_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        @if ($order->overall_status=='approved')
           
        <div class="col-md-6" id="customerDropdownContainer">
            <div class="form-group">
                <label for="customer_partner_id">Customer</label>
                <select name="customer_partner_id" id="customer_partner_id" class="form-control select2 {{ $errors->has('customer_partner_id') ? ' is-invalid' : '' }}" disabled>
                    <option value="">-- Select --</option>
                    @if ($order->customer_partner_id)
                        @foreach(App\Models\Partner::CustomerPartnerDropdown() as $customer_partner)
                            <option value="{{ $customer_partner->id }}" {{$order->customer_partner_id == $customer_partner->id ? 'selected' : '' }}>
                                {{Str::title($customer_partner->name) }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <input type="hidden" name="customer_partner_id" value="{{ $order->customer_partner_id }}">
                {!! $errors->first('customer_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        {{-- @else --}}
            
        {{-- <div class="col-md-6" id="customerDropdownContainer">
            <div class="form-group">
                <label for="customer_partner_id">Customer</label>
                <select name="customer_partner_id" id="customer_partner_id" class="form-control select2 {{ $errors->has('customer_partner_id') ? ' is-invalid' : '' }}" >
                    <option value="">-- Select --</option>
                    @if ($order->customer_partner_id)
                        @foreach(App\Models\Partner::CustomerPartnerDropdown() as $customer_partner)
                            <option value="{{ $customer_partner->id }}" {{$order->customer_partner_id == $customer_partner->id ? 'selected' : '' }}>
                                {{Str::title($customer_partner->name) }}
                            </option>
                        @endforeach
                    @endif
                </select>
               
                {!! $errors->first('customer_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        {{-- @endif --}}
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="overall_status">Order Status</label>
                <select name="overall_status" id="overall_status" class="form-control{{ $errors->has('overall_status') ? ' is-invalid' : '' }}">     
                    <option value=""> -- Select --</option>
                    <option value="approved" {{ $order->overall_status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="cancelled" {{ $order->overall_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="draft" selected {{ $order->overall_status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="completed" {{ $order->overall_status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ $order->overall_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="unapproved" {{ $order->overall_status === 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                </select>
                {!! $errors->first('overall_status', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="overall_adult">Adult </label>
                <input type="text" readonly value="{{$order->overall_adult}}" placeholder="adult" name="overall_adult" class="form-control {{($errors->has('overall_adult') ? ' is-invalid' : '')}}" id="overall_adult"  autofocus required>
                {!! $errors->first('overall_adult', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> <div class="col-md-6">
            <div class="form-group">
                <label for="overall_child">Child </label>
                <input type="text" readonly value="{{$order->overall_child}}" placeholder="child" name="overall_child" class="form-control {{($errors->has('overall_child') ? ' is-invalid' : '')}}" id="overall_child"  autofocus required>
                {!! $errors->first('overall_child', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> <div class="col-md-6">
            <div class="form-group">
                <label for="overall_bags">Bags </label>
                <input type="text" readonly value="{{$order->overall_bags}}" placeholder="bags" name="overall_bags" class="form-control {{($errors->has('overall_bags') ? ' is-invalid' : '')}}" id="overall_bags"  autofocus required>
                {!! $errors->first('overall_bags', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <input type="hidden" name="overall_status" id="req_status" value=''>
        <div class="col-md-6">
            <div class="form-group">
                <label for="booking_amount">Booking Amount </label>
                <input type="text" readonly value="{{$order->booking_amount}}"  name="booking_amount" class="form-control {{($errors->has('booking_amount') ? ' is-invalid' : '')}}" id="booking_amount"  autofocus>
                {!! $errors->first('booking_amount', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-group">
                <label for="final_amount">Final Amount </label>
                <input type="text" readonly value="{{$order->final_amount}}"  name="final_amount" class="form-control {{($errors->has('final_amount') ? ' is-invalid' : '')}}" id="final_amount"  autofocus>
                {!! $errors->first('final_amount', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
    </div>
        <br> --}}
        <div class="container"> 
            {{-- <div class=""> --}}
                {{-- <div class="col-auto"> --}}
                    <!-- Add Row Button -->
                    {{-- <button type="button" class="btn btn-outline-primary float-end" onclick="addRow()" data-toggle="tooltip" data-placement="left" title="Add Row">
                        <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </button> --}}
                {{-- </div> --}}
                <h2>Rides</h2>

            {{-- </div> --}}
            <div class="table-responsive">
                <table class="table table-bordered ">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Route</th>
                            <th>Rate</th>
                            <th>Status</th>
                            {{-- <th>Adult</th> --}}
                            {{-- <th>Child</th> --}}
                                    {{-- <th>Function</th>
                            <th>Return</th> --}}
                            {{-- <th>Bags</th> --}}
                            <th>Date</th>
                            <th>Pickup Time</th>
                            <th>Select Vehicle</th>
                            <th>Select Driver</th>
                            {{-- <th>CheckOut Time</th> --}}
                            {{-- <th>Is Ac</th> --}}
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">

                    </tbody>
                </table>
            </div>
        </div>


        
    </div>
    <div class="box-footer mt20">
        {{-- <input type="hidden" name="overall_status" id="req_status" value='draft'> --}}
        @php
        $model=[
            'notify_btn' => "Save",
            'function' => "Save",
            'body' => 'Please Confirm do you realy want to save?',
            'btn-color' => 'primary',
            'float' => "end mt-2",
            'id' => "save"
            ];
        @endphp
        @include('partials.modal', ['data'=>$model])
    </div>
    
  
    <script>
        $(document).ready(function() {
            // $('#customerDropdownContainer').css('display', 'none');
            // $('#business_partner_order_id').change(function() {
            // });

            $('#customer_partner_id').select2({
                    // val = ;
                    // width: auto,
                    width: '100%',
                    
                    ajax: {
                        url: function(){
                            return get_host()+'/partner/customer/'+$('#business_partner_order_id option:selected').val();
                        },
                        dataType: 'json',
                        delay: 250,

                        
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page,
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data,
                            };
                        },
                    },
                    minimumInputLength: 1,
                    escapeMarkup: function(m) {
                        return m;
                    },
                    placeholder: {
                        id: "",
                        text: "Select Customer"
                    },
                    templateResult: function(data) {
                        if (!data.id) {
                            return data.text;
                        }
                        var html = data.name + ' - ' + data.cnic;
                        // var html = '';
                        return html;
                    },
                    language: {
                        noResults: function() {
                            // return "hello";
                            var name = $('#customer_partner_id')
                            .data('select2')
                            .dropdown.$search.val();

                            //  "test"
                            return (
                                '<button type="button" data-name="'+name +'" class="btn btn-link add_new_supplier">'
                                +
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>&nbsp; ' 
                                + 'Add Customer'
                                +
                                '</button>'
                            );
                        },
                    },
            }).on('select2:select', function (e) {
                var data = e.params.data;
                $('#customer_partner_id').empty();
                $('#customer_partner_id').append('<option value="'+data.id+'" selected>'+data.name+'</option>');
            });

            hide_customer();
            $(document).on('click', '.add_new_supplier', function() {
                $('#customer_partner_id').select2('close');
                // var name = $(this).data('name');
                // $('.partner_modal')
                //     .find('input#name')
                //     .val(name);
                // $('.partner_modal')
                //     .find('select#contact_type')
                //     .val('supplier')
                //     .closest('div.contact_type_div')
                //     .addClass('hide');
                $('.partner_modal').modal('show');
            });
            
           
            // to handle update value in hidden fields in form:
            $('#overall_adult, #overall_child, #overall_bags').on('input', function() {
                var fieldId = $(this).attr('id');
                var fieldValue = $(this).val();
                console.log(fieldId,fieldValue);
                updateHiddenFieldsInAddedRows(fieldId, fieldValue);
            });

        });

        function hide_customer(){
            var selectedBusinessPartner = $('#business_partner_order_id option:selected').val();
            if (selectedBusinessPartner) {
                $('#business_partner_cust_id').val(selectedBusinessPartner);
                $('#customerDropdownContainer').css('display', 'unset');
            } else {
                $('#business_partner_cust_id').val('');
                $('#customerDropdownContainer').css('display', 'none');
            }
        }


        let rows_id = 1; // Initial row number
        let row_function_id = {}; 
        // Attach onchange event listener to original form fields
        
        function updateHiddenFieldsInAddedRows(fieldId, fieldValue) {
            // Iterate over added rows and update hidden field with the provided value
        
        
            $('.added-row').each(function() {
                var row_id = $(this).attr('id').split('_')[1];
                $(`#structure_adult_${row_id}`).val($('#overall_adult').val());
                $(`#structure_child_${row_id}`).val($('#overall_child').val());
                $(`#structure_bags_${row_id}`).val($('#overall_bags').val());
                console.log($(`#structure_bags_${row_id}`).val($('#overall_bags').val()));
            });
        }

        

        function addRow(structure = {}) {
            // console.log(structure);
            row_id = rows_id;
            row_index = rows_id-1;
            // row_function_id[row_id] = 1;
            console.log(structure);
            let newRow = `
                <tr id="structure_${row_id}"}" class="added-row">
                    <td>
                        ${row_id}
                        <input type="hidden"  id="structure_id_${row_id}" value="${structure.id??""}" name="rowIds[${row_index}]">
                        <input type="hidden"  value="${structure.adult??""}" class="" id="structure_adult_${row_id}" placeholder = "Enter adult no" name="adult[${row_index}]" autofocus required>
                        <input type="hidden"  value="${structure.child??""}" class="" id="structure_child_${row_id}" placeholder = "Enter child no" name="child[${row_index}]" autofocus required>
                        <input type="hidden"  value="${structure.bags??""}" class="" id="structure_bags_${row_id}" placeholder = "Enter bags no" name="bags[${row_index}]" autofocus required>
                        <input type="hidden" value="0"  id="structure_is_ac_hidden_${row_id}"  name="is_ac[${row_index}]" >
                        <input type="checkbox" ${structure.is_ac == 1?"checked":""} value="1"  id="structure_is_ac_${row_id}"  name="is_ac[${row_index}]" style="display: none;" >
                    </td>
                    
                    <td>
                        <select name='rate_list_id[${row_index}]' style='min-width:200px;' onchange='change_route(${row_id})' id='rate_list_id${row_id}' class='form-control' autofocus required disabled> 
                            <option value = "">-- Select -- </option>
                            @foreach(App\Models\RateList::RouteRateDropDown() as $route)
                                <option value='{{$route->id}}' price='{{$route->price}}' ${'{{$route->id}}' == structure.rate_list_id?'selected':''} >{{$route->name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" name='rate_list_id[${row_index}]' value="{{ $route->id}}">

                    </td>
                    <td>
                        <input type="text" readonly style='min-width:100px;' value="${structure.rate??""}"  id="rate_${row_id}"  class="form-control actions"   placeholder ="Rate" name="rate[${row_index}]" autofocus required>
                    </td>
                    
                    <td>
                        <select name='status[${row_index}]' style='min-width:200px;' id='status${row_id}' class='form-control' disabled > 
                            <option value = "">-- Select -- </option>
                            <option value="approved" ${ structure.status == 'approved'?'selected':''}>Approved</option>
                            <option value="cancelled" ${ structure.status == 'cancelled'?'selected':''}>Cancel</option>    
                            <option value="pending" selected ${ structure.status == 'pending'?'selected':''}>Pending</option>    
                        </select>
                        

                    </td>
                        
                    
                    <td><input type="date" readonly value="${structure.date??""}" class="form-control" id="structure_date_${row_id}" placeholder = "Enter Date" name="date[${row_index}]" autofocus required></td>
                    <td><input type="time" readonly value="${structure.pickup_time??""}" class="form-control" id="structure_pickup_time_${row_id}" placeholder = "Enter Pickup Time no" name="pickup_time[${row_index}]" autofocus required></td>
                    <td>
                        <select name='vehicle_id[${row_index}]' style='min-width:200px;' id='vehicle_id${row_id}' class='form-control' autofocus required > 
                            <option value = "">-- Select -- </option>
                            @foreach(App\Models\Vehicle::VehicleDropdown() as $vehicle)
                                <option value='{{$vehicle->id}}'  ${'{{$vehicle->id}}' == structure.vehicle_id ? 'selected':''} >{{$vehicle->car_company->name}}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name='driver_id[${row_index}]' style='min-width:200px;'  id='driver_id${row_id}' class='form-control' autofocus required > 
                            <option value = "">-- Select -- </option>
                            @foreach(App\Models\Partner::DriverDropdown() as $driver)
                                <option value='{{$driver->id}}'  ${'{{$driver->id}}' == structure.driver_id ? 'selected':''} >{{$driver->name}}</option>
                            @endforeach
                        </select>
                    </td>                   
                    
                    
                    <td>
                        <button type="button" class="btn btn-outline-danger  float-end" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
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
            

            $('#tableBody').append(newRow);
            rows_id++;

            return row_id;
		}

        function change_route(row_id){
            var price = $(`#rate_list_id${row_id} option:selected`).attr('price');
            $(`#rate_${row_id}`).val(price);
        }

        function deleteRow(row_id) {
        
            if (confirm("Are you sure you want to delete this row?")) {

                let structure_id = $('#structure_id_'+row_id).val();
                console.log(structure_id);
                if(structure_id){
                    ffsQuiet($.post('/delete-row/' + structure_id, {
                        "_token":"{{csrf_token()}}",
                        "_method":"DELETE",
                    })).then(function(data){
                        console.log(data);
                        $("#structure_"+row_id).remove();
                        toastr.options = {"positionClass": "toast-top-right",}
                        toastr.success("Row Removed Successfully", 'Success');

                    }).fail(function(xhr){
                        toastr.options = {"positionClass": "toast-top-right",}
                        toastr.error(xhr.responseJSON.error, 'Error');

                    })
                }else{
                    $("#structure_"+row_id).remove();
                    toastr.options = {"positionClass": "toast-top-right",}
                    toastr.success("Row Removed Successfully", 'Success');

                }
                
            }

        
        }
    
        async function load_edit(){
            @if (isset($edit))
                
                @foreach ($order->order_details as  $structure)
                    row_id = await addRow({!! $structure !!});
                    // for readonly for checkbox:
                    $('.readonly-checkbox').on('click', function(event) {
                    event.preventDefault();
                        });				
                @endforeach
            @endif
        }
        $(document).ready(function(){	
            load_edit();
            
           
        }) 

        //to restrict fields in numeric:
        $('#overall_adult').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
        $('#overall_child').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
        $('#overall_bags').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
      
       


    </script>
</div>

