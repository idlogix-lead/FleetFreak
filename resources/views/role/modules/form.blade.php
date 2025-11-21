<div class="col md-6">
    <div class="form-group">
        <label for="">Module Permissions</label>
        <select name="role_module_ids[]" multiple id="role_module_ids" class="form-control">
            @foreach(App\Models\RoleModule::getRoleModuleDropdown() as $module)
                <option value="{{$module->id}}" {{$role_modules?->where('role_module_id', $module->id)->first()?'selected':''}}>{{$module->name}}</option>
            @endforeach
        </select>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#role_module_ids').select2({
            width:'100%',
            multiple:true,
            placeholder: "Select modules for this role"
        });
    })
</script>
