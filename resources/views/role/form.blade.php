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
                    <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$role->name)}}">
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home">Home</label>
                    <input type="text" placeholder="Home" name="home" class="form-control {{($errors->has('home') ? ' is-invalid' : '')}}" id="home" value="{{old('home',$role->home)}}">
                    {!! $errors->first('home', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>
    <br>
    <div>
        <table class="table" style="width:100%;">
            <tr>
                <th>Module</th>
                <th></th>
                <th>Permissions</th>
            </tr>
            @foreach ($role_modules as $role_module)
                <tr>
                    <td>
                         {{ $role_module->name }} {{$role_module->is_report?'Report':''}}
                    </td>
                    <td>
                        <div class="form-group p-1">
                            <input type="checkbox" onclick='check_all("{{  $role_module->id }}")' name="" id="all_{{  $role_module->id }}">
                            <label for="all_{{  $role_module->id }}">Select All</label>
                        </div>
                    </td>
                    <td>

                        @php
                            $permissions = isset($role_permissions)?$role_permissions->where('role_module_id',$role_module->id):[];
                        @endphp
                        @foreach ($role_module->role_permission_type??[] as $structure)
                            @php
                                if(isset($role_permissions)){
                                    $permission_data = $permissions->where('role_permission_type_id',$structure->id)->first();
                                    $permission  = $permission_data->permission??0;
                                    $permission_id  = $permission_data->id??'';
                                }else{
                                    $permission  = 0;
                                    $permission_id  = '';
                                }
                            @endphp

                            <div class="form-group p-1">
                                <input type="hidden" name="permission[{{  $role_module->id }}][{{$structure->id}}][permission_id]" value='{{$permission_id}}'>
                                <input type="hidden" value='0' name="permission[{{  $role_module->id }}][{{$structure->id}}][permission]">
                                <input {{$permission?'checked':null}} name="permission[{{  $role_module->id }}][{{$structure->id}}][permission]"   type="checkbox" onclick='@if($structure->is_read == 1) check_other("{{  $role_module->id }}") @else check_read("perm_","{{$structure->id}}","{{  $role_module->id }}") @endif' class='all_{{  $role_module->id }} @if($structure->is_read != 1) other_{{  $role_module->id }} @endif {{$structure->is_read == 1? "read_": 'not_read_'}}{{  $role_module->id }} ' value='1' id="perm_{{$structure->id}}_{{  $role_module->id }}">
                                <label for="perm_{{$structure->id}}_{{  $role_module->id }}">{{$structure->action}}</label>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </table>
        <script>
            function check_all(module_id){
                if($('#all_'+module_id).is(':checked')){
                    $('.all_'+module_id).each(function(){
                        $(this).prop('checked',true);
                    })
                }else{
                    $('.all_'+module_id).each(function(){
                        $(this).prop('checked',false);
                    })
                }
            }
            function check_other(module_id){
                if(!$('.read_'+module_id).is(':checked')){
                    $('.other_'+module_id).each(function(){
                        $(this).prop('checked',false);
                    })
                }
            }

            function check_read(permission,struct_id,module_id){
                let id ="#"+permission+struct_id+"_"+module_id;
                // console.log(id);
                if($(id).is(':checked')){
                    $('.read_'+module_id).prop('checked',true);
                }
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
            //  $('#name').on('input', function() {
            //         var inputValue = $(this).val();
            //         // Remove non-numeric characters
            //         var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            //         // Limit to exactly 11 numbers
            //         // var elevenDigitValue = numericValue.slice(0, 11);
            //         // Update the input field value
            //         $(this).val(numericValue);
            //     });
            // $(document).ready(function(){

            // })
        </script>
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
