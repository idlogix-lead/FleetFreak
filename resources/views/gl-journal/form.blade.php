<div class="box box-info padding-1">
    @php
        $is_disabled = $glJournal?->status == 'completed';
        // if($is_disabled)
        $disabled = $is_disabled?'disabled':'';
    @endphp
    <div class="box-body">
        <div class="row">
            <div class="col-md-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="company_id">Company  </label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                    <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
                </div>
            </div>

            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="company_id">Company Id</label>
                    <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$glJournal->company_id}}">
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="company_id">Document No</label>
                    <input type="text" disabled placeholder="Document No" name="document_no" class="form-control {{($errors->has('document_no') ? ' is-invalid' : '')}}" id="document_no" value="{{$glJournal->document_no}}">
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="transaction_date">Transaction Date</label>
                    <input type="date" {{$disabled}} required  placeholder="Transaction Date" name="transaction_date" class="form-control {{($errors->has('transaction_date') ? ' is-invalid' : '')}}" id="transaction_date" value="{{$glJournal->transaction_date??date('Y-m-d')}}">
                    {!! $errors->first('transaction_date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="debit">Total Debit</label>
                    <input readonly required type="number" placeholder="Debit" name="debit" class="form-control {{($errors->has('debit') ? ' is-invalid' : '')}}" id="debit" value="{{$glJournal->debit}}">
                    {!! $errors->first('debit', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="credit">Total Credit</label>
                    <input readonly required type="number" placeholder="Credit" name="credit" class="form-control {{($errors->has('credit') ? ' is-invalid' : '')}}" id="credit" value="{{$glJournal->credit}}">
                    {!! $errors->first('credit', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-xl-3">
                <div class="form-group">
                    <label for="status">Document Status</label>
                    {{-- <select name="status" id="status" required readonly class="form-control">
                        <option value="draft">Draft</option>
                        <option value="completed">Completed</option>
                    </select> --}}
                    <input type="hidden" name="status" value='{{ucfirst($glJournal?->status)}}'>
                    <input type="text" id="status" value='{{ucfirst($glJournal?->status)}}' required readonly class="form-control">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea {{$disabled}} name="description" id="description" placeholder="Description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}"  cols="30" rows="2">{{$glJournal?->description}}</textarea>
                    {{-- <input type="text"name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$glJournal->description}}"> --}}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            @if(!$is_disabled)
                <div class="col-md-12">
                    <div class="float-end">
                        <div class="btn btn-sm btn-outline-primary" onclick='complete_document()'>Complete</div>
                    </div>
                </div>
            @endif

        </div>
    </div>



    <hr>
    <style>
        .gljournal-table tbody tr td{
            padding:0px;
        }
        .gljournal-table tbody tr td:nth-child(2) {
            max-width: 200px;
            min-width: 150px;
        }
        .gljournal-table tbody tr td:nth-child(1) {
            max-width: 80px;
        }
        .gljournal-table tbody tr td input, .gljournal-table tbody tr td select{
            /* padding:0px;
            width:100%;
            height:100%; */
            border-color:transparent;
            border-radius:0px;
        }
        .gljournal-table tbody tr td input:focus,
         .gljournal-table tbody tr td select:focus{
            /* padding:0px;
            width:100%;
            height:100%; */
            box-shadow:0px 0px 0px transparent;
        }
        /* .rm-row:hover{
            fill:  green;
            cursor: pointer;
        } */
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
    <div class="row">
        @if(!$is_disabled)
            <div class="col-md-12">
                <div class="btn btn-outline-primary float-end btn-sm" onclick='new_row_in_db()'>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
                </div>
            </div>
        @endif
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table gljournal-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Account</th>
                            <th>Partner</th>
                            <th>Product</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id='gljournal-table-tbody'>
                    </tbody>
                </table>
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
<script>
    var row_index = -1;
    var row_no = 0;
    let state = 1;
    async function add_gl_row(row){
        row_no = row_no+1;
        row_index = row_index+1;
        row_id = row.id;

        let row_html = `
            <tr id='row_${row_id}'>
                <td><input type="number" required tabindex="-1" readonly value='${row_no}' name="rows[${row_index}][no]" id="no_${row_index}" class="form-control"></td>
                <td>
                    <input type='hidden' value="${row_id}" name="rows[${row_index}][row_id]">
                    <input type='hidden' value="" name="rows[${row_index}][account_id]">
                    <select required {{$disabled}} name="rows[${row_index}][account_id]" id="account_id_${row_index}" class="form-control select2 account">
                        <option value=""  selected>--Select--</option>
                        @foreach(App\Models\Account::dropdown([],false) as $account)
                            <option value="{{$account->id}}" ${row.account_id == {{$account->id}}?'selected':''}>{{$account->code}} - {{$account->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type='hidden' value="" name="rows[${row_index}][partner_id]">
                    <select {{$disabled}} name="rows[${row_index}][partner_id]" id="partner_id_${row_index}" class="form-control select2 ">
                        <option value=""  selected>--Select--</option>
                        @foreach(App\Models\Partner::BusinessPartnerDropdown() as $partner)
                            <option value="{{$partner->id}}" ${row.partner_id == {{$partner->id}}?'selected':''}>{{$partner->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type='hidden' value="" name="rows[${row_index}][product_id]">
                    <select {{$disabled}} name="rows[${row_index}][product_id]" id="product_id_${row_index}" class="form-control select2 ">
                        <option value=""  selected>--Select--</option>
                        @foreach(App\Models\Product::dropdown() as $product)
                            <option value="{{$product->id}}" ${row.product_id == {{$product->id}}?'selected':''}>{{$product->name}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input {{$disabled}} type="text" required value="${row.debit}" placeholder="Debit Amount" name="rows[${row_index}][debit]" id="debit_${row_index}" oninput='$("#credit_${row_index}").val(0)' class="form-control row_debit numeric default-0"></td>
                <td><input {{$disabled}} type="text" required value="${row.credit}" placeholder="Credit Amount" name="rows[${row_index}][credit]" id="credit_${row_index}" oninput='$("#debit_${row_index}").val(0)' class="form-control row_credit numeric default-0"></td>
                <td><input {{$disabled}} type="text" required value="${row.quantity}" placeholder="Quantity" name="rows[${row_index}][quantity]" id="quantity_${row_index}" class="form-control numeric default-0"></td>
                <td><input {{$disabled}} type="text" value="${row.description??''}" placeholder="Details" name="rows[${row_index}][description]" id="description_${row_index}" class="form-control"></td>
                <td>
                    @if(!$is_disabled)
                        <div class=" text-danger rm-row  float-end mt-1" onclick="destroy_row(${row_id},${row_no},${row_index})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-delete"><path d="M21 4H8l-7 8 7 8h13a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2z"></path><line x1="18" y1="9" x2="12" y2="15"></line><line x1="12" y1="9" x2="18" y2="15"></line></svg>
                        </div>
                    @endif
                </td>
            </tr>
        `;
        $('#gljournal-table-tbody').append(row_html);
        $(`#account_id_${row_index}`).select2({ width: '100%' });
        $(`#product_id_${row_index}`).select2({ width: '100%' });
        $(`#partner_id_${row_index}`).select2({ width: '100%' });
        checkSelect2Validity();

    }

    function calculate_debit_credit(){
        let total_debit = 0;
        let total_credit = 0;
        $('.row_debit').each(function(){
            if($(this).val()){
                total_debit = parseFloat(total_debit) + parseFloat($(this).val());
            }
        })
        $('.row_credit').each(function(){
            if($(this).val()){
                total_credit = parseFloat(total_credit) + parseFloat($(this).val());
            }
        })

        $('#debit').val(total_debit);
        $('#credit').val(total_credit);

        return {
            'total_debit':total_debit,
            'total_credit':total_credit,
        }
    }
    function load_rows(){
        // gljournallines
        @foreach($glJournal?->gljournallines as $row)
            add_gl_row({!! $row !!})
        @endforeach
    }
    function set_save_state(state_from_save_state){
        state = state_from_save_state;

        let spinner = `<div class="spinner-border text-primary" style='width:16px;height:16px;' role="status">
            <span class="visually-hidden">Loading...</span>
        </div>`;
        if(state == 1){
            $('#state').html('Saved');
        }else if(state == 2){
            $('#state').html(spinner+ ' Saving');
            // loader()
        }else{
            $('#state').html('Not saved');
        }
    }
    @if(!$is_disabled)
        async function save_state() {
            await calculate_debit_credit();
            set_save_state(2);
            const formData = {};
            await $('#gljournal_form').serializeArray().forEach(function(field) {
                const name = field.name;
                const value = field.value;

                // Check if the field name has nested structure (like rows[4][no])
                const match = name.match(/^([a-zA-Z0-9_]+)(\[(.*?)\])?/);

                if (match) {
                    let currentLevel = formData;
                    const parts = name.split(/[\[\]]+/).filter(Boolean);  // Split by brackets, remove empty parts

                    for (let i = 0; i < parts.length - 1; i++) {
                        const part = parts[i];
                        currentLevel[part] = currentLevel[part] || (isNaN(parts[i + 1]) ? {} : []);
                        currentLevel = currentLevel[part];
                    }
                    currentLevel[parts[parts.length - 1]] = value;
                } else {
                    formData[name] = value;
                }
            });

            // const rowData = {
            //     row_id: $(`input[name="rows[${rowIndex}][row_id]"]`).val(),
            //     account_id: $(`select[name="rows[${rowIndex}][account_id]"]`).val(),
            //     debit: $(`input[name="rows[${rowIndex}][debit]"]`).val(),
            //     credit: $(`input[name="rows[${rowIndex}][credit]"]`).val(),
            //     quantity: $(`input[name="rows[${rowIndex}][quantity]"]`).val(),
            //     description: $(`input[name="rows[${rowIndex}][description]"]`).val(),
            // };
            // console.log(formData);

            // AJAX request using $.post
            $.post({
                url: "{{ route('gl-journals.update', $glJournal->id) }}",  // Replace with your server URL
                contentType: 'application/json',  // Set content type to JSON
                data: JSON.stringify(formData),
                success: function(response) {
                    console.log('Form saved successfully:', response);
                    set_save_state(1);

                    // msgboxbox.show(response.message, 'success', null); // Display success message
                },
                error: function(xhr, status, error) {
                    console.error('Form save failed:', error);
                    // msgboxbox.show(xhr.responseJSON.error??'Unknown error occured', 'error', null); // Display error message
                    set_save_state(0);
                }
            });
        }
        async function new_row_in_db() {
            try {
                loader();
                let row = await $.post("{{route('gl-journal.add_row', $glJournal->id)}}", {
                    _token: '{{csrf_token()}}',
                });
                console.log(row);

                await add_gl_row(row);
                endloader();
                // Assuming the server returns JSON format, parse and log the response
                // console.log(resp);
                // return row;
            } catch (error) {
                endloader();
                msgboxbox.show(error.responseJSON.error,'error',null);
                console.error("An error occurred:", error);
            }
        }
        async function destroy_row(row_id, row_no, row_index){
            // gl-journals/destroy/row
            if(confirm('Are you sure you want to remove Row no '+row_no)){
                try {
                    loader();
                    response = await $.post(get_host()+'/gl-journals/destroy/row/'+row_id, {
                        _token: "{{csrf_token()}}",
                    });

                    $('#row_'+row_id).remove();
                    calculate_debit_credit();
                    endloader();
                    msgboxbox.show(response.message,'success',null);
                } catch (error) {
                    endloader();
                    msgboxbox.show(error.responseJSON.error,'error',null);
                    // console.error("An error occurred:", error.responseJSON.message);
                }
            }

        }
        function complete_document(){
            if(valid_form()){
                if(check_totals()){
                    ffsQuiet($.post("{{route('gl-journal.complete', $glJournal->id)}}",{
                        '_token':"{{csrf_token()}}"
                    })).then(function(resp){
                        msgboxbox.show(resp.message, 'success', null);
                        location.reload();
                    }).fail(function(xhr){
                        msgboxbox.show(xhr.responseJSON.error, 'error', null); // Display error message
                    })
                }else{
                    msgboxbox.show("Total Debit & Total Credit are not Equal or Zero", 'error', null); // Display error message
                }
            }
        }
    @endif
    function valid_form(){
        let isValid = true;
        if (!$('#gljournal_form')[0].checkValidity()) {
            isValid = false;
            $('#gljournal_form')[0].reportValidity(); // Shows default popup instructions for invalid fields
        }
        return isValid;
    }
    function check_totals(){
        let totals = calculate_debit_credit();

        // {
        //     'total_debit':total_debit,
        //     'total_credit':total_credit,
        // }
        return (totals.total_debit == totals.total_credit && totals.total_debit != 0)?true:false;


    }

    $(document).on('input', '.numeric', function() {
        // this.value = this.value.replace(/[^0-9]/g, ''); // Remove any non-numeric characters
        this.value = this.value.replace(/[^0-9.]/g, ''); // Remove any non-numeric and non-decimal characters
        if ((this.value.match(/\./g) || []).length > 1) { // Allow only one decimal point
            this.value = this.value.slice(0, -1);
        }
    });
    function checkSelect2Validity() {
        $('.select2[required]').each(function() {
            const selectElement = $(this);
            // alert(1);
        if (selectElement.val() === null || selectElement.val() === '') {
                selectElement.siblings('.select2').find('.select2-selection').addClass('error');
            // alert(2);
        } else {
                selectElement.siblings('.select2').find('.select2-selection').removeClass('error');
            // alert(3);
        }
        });
    }
    $(document).ready(function(){
        // Set interval to check every 5 seconds (5000 milliseconds)
        setInterval(async function() {
            if (state === 0) {
                await save_state();
            }
        }, 5000);

        // Check all Select2 elements on page load
        // checkSelect2Validity();

        // Check on change of any Select2 element
        $(document).on('change','.select2', function() {
            checkSelect2Validity();
        });

        $(document).on('change','#gljournal_form input, #gljournal_form select, #gljournal_form textarea',async function() {
            await save_state();
        });
        $(document).on('input','#gljournal_form input, #gljournal_form select, #gljournal_form textarea',async function() {
            // await save_state();
            set_save_state(0);
        });

        // $('.select2').each(function(){
        //     $(this).select2({ width: '100%' });
        // });
        load_rows();
        $(document).on('input', '.row_debit, .row_credit',function(){
            calculate_debit_credit();
        });
        // add_gl_row();

        // $('#btn_save').click(function(event){
        //     event.preventDefault(); // Prevent the default behavior of opening the modal

        //     // Check condition here
        //     var condition = false; // Replace this with your actual condition

        //     if (condition) {
        //     // If condition is true, manually open the modal
        //         var myModal = new bootstrap.Modal($('#exampleModalsave')[0], {});
        //         myModal.show();
        //     } else {
        //     // Optional: Notify the user that the condition was not met
        //     alert('Condition not met.');
        //     }
        // });
    });

    $(document).on('change','.default-0', function() {
        if ($(this).val() === '') {
            $(this).val('0');
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        // Confirmation for all link clicks
        // document.querySelectorAll("a").forEach(link => {
        //     link.addEventListener("click", function (event) {
        //         if (!confirm("Are you sure you want to leave this page?") && state == 2) {
        //             event.preventDefault();
        //         }
        //     });
        // });

        // Confirmation for all form submissions
        // document.querySelectorAll("form").forEach(form => {
        //     form.addEventListener("submit", function (event) {
        //         if (!confirm("Are you sure you want to submit this form?") && state == 2) {
        //             event.preventDefault();
        //         }
        //     });
        // });

        // Confirmation for page refresh or browser navigation
        window.addEventListener("beforeunload", function (event) {
            if(state == 2 || state == 0){
                event.preventDefault();
                event.returnValue = "Are you sure you want to leave this page?";
            }
        });
    });
</script>




