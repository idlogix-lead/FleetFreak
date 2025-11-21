<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$roleModule->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="actor_id">Actor Id</label>

                    <select name="actor_id[]" id="actor_id" class="form-control" multiple>
                    @foreach($actors as $actor)
                        <option value="{{ $actor->id }}"  {{$roleModule->role_module_actors->where('actor_id', $actor->id)->first() ? 'selected':''}}>{{ $actor->name }}</option>
                    @endforeach
                </select> 
            </div>
        </div> --}}

        </div>
        <br>


        <div class="container">
            <h2>Table</h2>
            <div class="row justify-content-end mb-3">
                <div class="col-auto">
                    <!-- Add Row Button -->
                    <button type="button" class="btn btn-outline-primary" onclick="addRow()" data-toggle="tooltip" data-placement="left" title="Add Row">
                        <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </button>
              </div>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
						<th>Method</th>
						<th>Is Read</th>
								{{-- <th>Function</th>
                        <th>Return</th> --}}
                        <th>Error Message</th>
                        <th>Operation</th>
                    </tr>
                </thead>
                <tbody id="tableBody">

                </tbody>
            </table>
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
@include('role-module.table_crud')
<script>
    document.getElementById('name').addEventListener('keydown', function(event) {
    // Check if the pressed key is space (keyCode 32) and prevent default behavior
    if (event.keyCode === 32) {
        event.preventDefault();
    }
});
</script>
