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
                    <label for="account_type_id">Account Type</label>
                    <select name="account_type_id" autofocus required id="account_type_id" class="form-control {{($errors->has('account_type_id') ? ' is-invalid' : '')}}">
                        <option value="" >--Select--</option>
                        @foreach (\App\Models\AccountType::dropdown([], true); as $type)
                            <option value="{{$type->id}}" {{$account?->account_type_id == $type->id? 'selected':''}} code='{{$type->code}}'>{{$type->code}} - {{$type->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('account_type_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account_subtype_id">Account Subtype</label>
                    <select name="account_subtype_id" autofocus required id="account_subtype_id" class="form-control {{($errors->has('account_subtype_id') ? ' is-invalid' : '')}}">
                        <option value="" >--Select--</option>
                    </select>
                    {!! $errors->first('account_subtype_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <script>
                $(document).ready(function(){

                    $('#account_type_id').select2({
                        placeholder: 'Select Account Type',
                        width: '100%'
                    });
                       $('#name').on('input', function() {
                    var inputValue = $(this).val();
                    // Remove non-numeric characters
                    var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
                    // Limit to exactly 11 numbers
                    // var elevenDigitValue = numericValue.slice(0, 11);
                    // Update the input field value
                    $(this).val(numericValue);
                });

                    // Initialize account_subtype select2 but disable it initially
                    $('#account_subtype_id').select2({
                        placeholder: 'Select Account Subtype',
                        width: '100%',
                        // minimumInputLength: 2
                    })
                    .prop('disabled', true); // Disable by default

                    // Function to load subtypes based on selected account type
                    function loadAccountSubtypes(accountTypeId) {
                        console.log("Fetching subtypes for account type ID: ", accountTypeId); // Debug log

                        // Fetch subtypes from the server
                        $.ajax({
                            url: '/account-types/dropdown/' + accountTypeId,
                            dataType: 'json',
                            type: 'GET',
                            success: function(data) {
                                console.log("AJAX response data: ", data); // Debug log to check if data is received

                                $('#account_subtype_id').empty(); // Clear current options


                                // Ensure that data is an array
                                if (Array.isArray(data)) {
                                    $('#account_subtype_id').append(`<option code='' value=''>--Select--</option>`); // Clear current options
                                    // Populate the dropdown with new data
                                    $.each(data, function(index, item) {
                                        let newOption = new Option(item.code + ' - ' + item.name, item.id, false, false);
                                        $(newOption).attr('code', item.code);
                                        if({{$account?->account_subtype_id??'null'}} == item.id){
                                            $(newOption).attr('selected', true); // Set code attribute
                                        } // Set code attribute
                                        $('#account_subtype_id').append(newOption);
                                    });

                                    // Enable the select2 control and trigger change
                                    $('#account_subtype_id').prop('disabled', false).trigger('change');
                                } else {
                                    console.error("Invalid data format. Expected an array of objects.");
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching subtypes:", status, error);
                                $('#account_subtype_id').prop('disabled', true); // Disable if error occurs
                            }
                        });
                    }

                    // Listen for changes in account_type_id
                    $('#account_type_id').on('change', function() {
                        let accountTypeId = $(this).val();
                        if (accountTypeId) {
                            loadAccountSubtypes(accountTypeId); // Load subtypes for selected account type
                        } else {
                            $('#account_subtype_id').prop('disabled', true).empty(); // Disable and clear subtypes if no type is selected
                        }
                    });

                    // Preselect values if they exist
                    // if ({{$account->account_subtype_id ?? 0}}) {
                    //     let newOption = new Option('{{$account->accountSubType?->code}} - {{$account->accountSubType?->name}}', {{$account->account_subtype_id ?? 'null'}}, true, true);
                    //     $(newOption).attr('code', '{{$account->accountSubType?->code}}');
                    //     $('#account_subtype_id').append(newOption).trigger('change');
                    // }

                    if ({{$account->account_type_id ?? 0}}) {
                        // Load subtypes for the pre-selected account type
                        loadAccountSubtypes({{$account?->account_type_id}});
                    }

                    // Auto-generate the code when both account type and subtype are selected
                    $('#account_type_id, #account_subtype_id').on('select2:select', async function() {
                        let selectedAccountType = $('#account_type_id').val();
                        let selectedAccountSubType = $('#account_subtype_id').val();

                        if (selectedAccountType && selectedAccountSubType) {
                            let code = await generateCode(selectedAccountType, selectedAccountSubType);
                            if(selectedAccountSubType == {{$account?->account_subtype_id??'null'}}){
                                $('#code').val("{{$account?->code}}");
                            }else{
                                $('#code').val(code);
                            }
                        }
                    });

                    if({{$account->account_subtype_id??0}}){
                        // let newOption = new Option('{{$account->accountSubType?->code}} - {{$account->accountSubType?->name}}', {{$account->account_subtype_id??'null'}}, true, true);
                        // $(newOption).attr('code', '{{$account->accountSubType?->code}}');
                        // $('#account_subtype_id').append(newOption).trigger('change');
                        // $('#account_subtype_id').select2('close');
                    }
                    // check_subtype();

                });

                // function check_subtype(){
                //     if({{$account->account_subtype_id??0}}){
                //         // let newOption = new Option('{{$account->accountSubType?->code}} - {{$account->accountSubType?->name}}', {{$account->account_subtype_id??'null'}}, true, true);
                //         // $(newOption).attr('code', '{{$account->accountSubType?->code}}');
                //         $('#account_subtype_id').val({{$account->account_subtype_id}}).trigger('change');
                //         // $('#account_subtype_id').select2('close');
                //     }
                // }
                async function generateCode(account_type_id, account_subtype_id) {
                    // accounts/account-type-no/{account_type_id}/{account_subtype_id}
                    try {
                        const no = await $.get(`/accounts/account-type-no/${account_type_id}/${account_subtype_id}`);

                        const selectedAccountTypeText = $('#account_type_id').find('option:selected').attr('code');
                        const selectedAccountSubTypeText = $('#account_subtype_id').find('option:selected').attr('code');

                        // console.log(selectedAccountTypeText, selectedAccountSubTypeText, no);

                        if (selectedAccountTypeText && selectedAccountSubTypeText && no) {
                            // console.log('sd');
                            return `${selectedAccountTypeText}-${selectedAccountSubTypeText}-${no}`;
                        } else {
                            // console.log('null');
                            return null;
                        }
                    } catch (error) {
                        console.error('Error fetching account type number:', error);
                        return null;
                    }

                }

            </script>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" placeholder="Name" autofocus required name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$account->name}}">
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="code">Code</label>
                    <input type="text" placeholder="Code" readonly autofocus required name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{$account->code}}">
                    {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="hidden" value='0' name="is_active" id="is_active_hidden">
                    <input type="checkbox" value='1'  name="is_active" id="is_active" {{$account->is_active !== NULL ? ($account?->is_active == 1?'checked':''):'checked'}}>
                    <label for="is_active">Is Active</label>

                    {{-- <label for="is_active">Is Active</label>
                    <input type="text" placeholder="Is Active" name="is_active" class="form-control {{($errors->has('is_active') ? ' is-invalid' : '')}}" id="is_active" value="{{$account->is_active}}"> --}}
                    {{-- {!! $errors->first('is_active', '<div class="invalid-feedback">:message</div>') !!} --}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <input type="hidden" value='0' name="is_summary" id="is_active_hidden">
                    <input type="checkbox" value='1'  name="is_summary" id="is_summary" {{$account->is_summary !== NULL ? ($account?->is_summary == 1?'checked':''):'checked'}}>
                    {{-- <label for="is_active">Is Active</label> --}}

                    <label for="is_summary">Is Summary</label>
                    {{-- <input type="text" placeholder="Is Summary" name="is_summary" class="form-control {{($errors->has('is_summary') ? ' is-invalid' : '')}}" id="is_summary" value="{{$account->is_summary}}">
                    {!! $errors->first('is_summary', '<div class="invalid-feedback">:message</div>') !!} --}}
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="company_id">Company Id</label>
                    <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$account->company_id}}">
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" cols="15" rows="5">{{$account->description}}</textarea>
                    {{-- <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$account->description}}"> --}}
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>
    <div class="box-footer mt20">
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
    </div>
</div>
