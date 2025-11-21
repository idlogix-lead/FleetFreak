<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" autofocus required placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$accountType->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" autofocus required placeholder="Code" name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{$accountType->code}}">
                {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="parent_id">Select Level 1 Account Type</label>
                @php
                    $ignore = [];
                    if(!$accountType->parent_id && $accountType->id){
                        $ignore[] = $accountType->id;
                    }
                @endphp
                <select name="parent_id" id="parent_id" autofocus class="form-control {{($errors->has('parent_id') ? ' is-invalid' : '')}}">
                    <option value="" >--Select--</option>
                    @foreach (\App\Models\AccountType::dropdown($ignore, true); as $type)
                        <option value="{{$type->id}}" {{$accountType?->parent_id == $type->id? 'selected':''}}>{{$type->code}} - {{$type->name}}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Parent Id" name="parent_id" class="form-control {{($errors->has('parent_id') ? ' is-invalid' : '')}}" id="parent_id" value="{{$accountType->parent_id}}"> --}}
                {!! $errors->first('parent_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="hidden" value='0' name="is_active" id="is_active_hidden">
                <input type="checkbox" value='1'  name="is_active" id="is_active" {{$accountType->is_active !== NULL ? ($accountType?->is_active == 1?'checked':''):'checked'}}>
                <label for="is_active">Is Active</label>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" cols="15" rows="5">{{$accountType->description}}</textarea>
                {{-- <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$accountType->description}}"> --}}
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
