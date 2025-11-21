<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$role->name}}">
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="home">Home</label>
                    <input type="text" placeholder="Home" name="home" class="form-control {{($errors->has('home') ? ' is-invalid' : '')}}" id="home" value="{{$role->home}}">
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
                        {{ $role_module->name }}
                    </td>
                    <td>
                        <div class="form-group p-1">
                            <input type="checkbox" onclick='check_all("{{  $role_module->id }}")' name="" id="all_{{  $role_module->id }}">
                            <label for="all_{{  $role_module->id }}">Select All</label>
                        </div>
                    </td>
                    <td>
                        @php
                            $permission = isset($role_permissions)?$role_permissions->where('role_module_id',$role_module->id)->first():null;
                            // dd($permission);
                        @endphp
                        <input type="hidden" name="permission_id[{{  $role_module->id }}]" value='{{ $permission?$permission->id:null }}'>
                        <div class="form-group p-1">
                            <input type="hidden"  value='0' name="read[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_other("{{  $role_module->id }}")' class='all_{{  $role_module->id }}' {{ $permission?$permission->read?'checked':null:null }} value='1' name="read[{{  $role_module->id }}]" id="read_{{  $role_module->id }}">
                            <label for="read_{{  $role_module->id }}">Read</label>
                        </div>
                        <div class="form-group p-1">
                            <input type="hidden" value='0' name="create[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_read("create_","{{  $role_module->id }}")' class='all_{{  $role_module->id }} other_{{  $role_module->id }}' {{ $permission?$permission->create?'checked':null:null }} value='1' name="create[{{  $role_module->id }}]" id="create_{{  $role_module->id }}">
                            <label for="create_{{  $role_module->id }}">Create</label>
                        </div>
                        <div class="form-group p-1">
                            <input type="hidden" value='0' name="update[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_read("update_","{{  $role_module->id }}")' class='all_{{  $role_module->id }} other_{{  $role_module->id }}' {{ $permission?$permission->update?'checked':null:null }} value='1' name="update[{{  $role_module->id }}]" id="update_{{  $role_module->id }}">
                            <label for="update_{{  $role_module->id }}">Update</label>
                        </div>
                        <div class="form-group p-1">
                            <input type="hidden" value='0' name="delete[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_read("delete_","{{  $role_module->id }}")' class='all_{{  $role_module->id }} other_{{  $role_module->id }}' {{ $permission?$permission->delete?'checked':null:null }} value='1' name="delete[{{  $role_module->id }}]" id="delete_{{  $role_module->id }}">
                            <label for="delete_{{  $role_module->id }}">Delete</label>
                        </div>
                        <div class="form-group p-1">
                            <input type="hidden" value='0' name="recover[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_read("recover_","{{  $role_module->id }}")' class='all_{{  $role_module->id }} other_{{  $role_module->id }}' {{ $permission?$permission->recover?'checked':null:null }} value='1' name="recover[{{  $role_module->id }}]" id="recover_{{  $role_module->id }}">
                            <label for="recover_{{  $role_module->id }}">Recover</label>
                        </div>
                        <div class="form-group p-1">
                            <input type="hidden" value='0' name="global[{{  $role_module->id }}]">
                            <input type="checkbox" onclick='check_read("global_","{{  $role_module->id }}")' class='all_{{  $role_module->id }} other_{{  $role_module->id }}' {{ $permission?$permission->global?'checked':null:null }} value='1' name="global[{{  $role_module->id }}]" id="global_{{  $role_module->id }}">
                            <label for="global_{{  $role_module->id }}">Global</label>
                        </div>

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
                if(!$('#read_'+module_id).is(':checked')){
                    $('.other_'+module_id).each(function(){
                        $(this).prop('checked',false);
                    })
                }
            }
            
            function check_read(permission,module_id){
                if($("#"+permission+module_id).is(':checked')){
                    $('#read_'+module_id).prop('checked',true);
                }
            }

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