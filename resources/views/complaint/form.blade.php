<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="customer_id">Customer Id</label>
                <input type="text" placeholder="Customer id" name="customer_id" class="form-control {{($errors->has('customer_id') ? ' is-invalid' : '')}}" id="customer_id" value="{{$complaint->customer_id}}">
                {!! $errors->first('customer_i', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="machine_id">Machine Id</label>
                <input type="text" placeholder="Machine Id" name="machine_id" class="form-control {{($errors->has('machine_id') ? ' is-invalid' : '')}}" id="machine_id" value="{{$complaint->machine_id}}">
                {!! $errors->first('machine_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="problem_statment">Problem Statment</label>
                <input type="text" placeholder="Problem Statment" name="problem_statment" class="form-control {{($errors->has('problem_statment') ? ' is-invalid' : '')}}" id="problem_statment" value="{{$complaint->problem_statment}}">
                {!! $errors->first('problem_statment', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" placeholder="Date" name="date" class="form-control {{($errors->has('date') ? ' is-invalid' : '')}}" id="date" value="{{$complaint->date}}">
                {!! $errors->first('date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Priority</label>
                <select name="status" class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" id="status">
                    <option value="Select status" disabled selected>Select Status</option>
                    <option value="normal" {{ $complaint->status == "normal" ? 'selected' : '' }}>Normal</option>
                    <option value="high" {{ $complaint->status == "high" ? 'selected' : '' }}>High</option>
                    <option value="Urgent" {{ $complaint->status == "urgent" ? 'selected' : '' }}>Urgent</option>
                </select>
                {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
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

    <div class="box-footer mt20">
        @php
        $edit = [
            'notify_btn' => "Save",
            'function' => "Save",
            'body' => 'modalForm' . $complaint->id,
            'btn-color' => 'primary',
            'float' => "end mt-2",
            'id' => "save"
        ];
        @endphp
        @include('complaint.edit', ['edit'=> $edit])
    </div>
</div>