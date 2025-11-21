<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" placeholder="Name" id ="name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$actor->name}}">
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Modules</label>
                    <select name="role_module_id[]" id="role_module_id" class="form-control select2  {{($errors->has('role_module_id') ? ' is-invalid' : '')}}" multiple>
                        @foreach (App\Models\RoleModule::get() as $module)
                            <option value="{{$module->id}}" {{App\Models\RoleModuleActors::where('actor_id', $actor->id)->where('role_module_id', $module->id)->first()?'selected':''}}>{{$module->name}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('role_module_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$actor->description}}">
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
<script>
    document.getElementById('name').addEventListener('keydown', function(event) {
        // Check if the pressed key is space (keyCode 32) and prevent default behavior
        if (event.keyCode === 32) {
            event.preventDefault();
        }
    });
    $(document).ready(function(){
        $('#role_module_id').select2({
            width:'100%',
            multiple:true,
        })
    });
</script>
