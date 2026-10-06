<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" required placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$activity->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$activity->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
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
        @php
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
            <button type="button" class="btn btn-outline-primary float-end" onclick="openModal()" data-toggle="tooltip" data-placement="left" title="Add Row">
                <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </button>
        {{-- </div> --}}
        <h2>Activity Lines</h2>

    </div>
    <div class="table-responsive">
        <table class="table table-bordered ">
            <thead>
                <tr>

                    <th>Seq No</th>
                    <th>Name <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                    <th>Description <span>
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
    @php
        $model = [
            'notify_btn' => 'Save & Submit',
            'function' => 'Submit Request',
            'body' => 'Please Confirm do you really want to Submit Request?',
            'btn-color' => 'success',
            'float' => 'end mt-2',
            'id' => 'submit_req',
        ];
    @endphp
    @include('partials.modal', ['data' => $model])
</div>

<script>
    var row_index = -1;
    let rows_id = 1;
    let seqNo = 1;
    async function add_activityline_row(row={}){
        row_index = row_index+1;
        row_id = rows_id;
        // Use the current value of seqNo or the one provided in the row data
        let seqNoValue = row.seq_no ?? seqNo;

        let row_html = `
            <tr id='row_${row_id}'>

                <td>

                    <input type="hidden"  id="row_id_${row_id}" value="${row.id??""}" name="rows[${row_index}][row_id]]">
                    <input  type="text" required value="${seqNoValue}" placeholder="Seq No" name="rows[${row_index}][seq_no]" id="seq_no_${row_index}" class="form-control" onchange="change_number(this)"></td>
                <td><input  type="text" required value="${row.name??""}" placeholder="Name" name="rows[${row_index}][name]" id="name_${row_index}" class="form-control" onchange="change_name(this)">
                    <div class="error-message text-danger" id="error_name_${row_index}"></div></td>
                <td><input  type="text" value="${row.description??""}" placeholder="Description" name="rows[${row_index}][description]" id="quantity_${row_index}" class="form-control"></td>
                <td>
                    <input type="hidden" value="0"  id="is_active_hidden_${row_id}"  name="rows[${row_index}][is_active]" >
                    <input style="margin-top:10px;" type="checkbox" value="1" placeholder="Is Active" ${row.is_active == 1?"checked":""} name="rows[${row_index}][is_active]" id="is_active_${row_index}"></td>
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
        $('#tableBody').append(row_html);
        rows_id++;
        seqNo++;
        return row_id;
        // checkSelect2Validity();

    }


    function openModal() {
        add_activityline_row();
    }

    function deleteRow(row_id) {

        if (confirm("Are you sure you want to delete this row?")) {
            console.log('row id is:'+row_id);

            let structure_id = $('#row_id_' + row_id).val();

            console.log(structure_id);
            if (structure_id) {
                ffsQuiet($.post('/delete-activity-row/' + structure_id, {
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
        @foreach ($activity->activityLines as $row)
        // var activity = @json($activity);
            console.log("this is edit activity:", {!! $row !!});

            row_id = await add_activityline_row({!! $row !!});
        @endforeach
    }

    $(document).ready(async function(){
        await load_edit();
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

</script>
{{-- @dd($activity->activityLines) --}}
