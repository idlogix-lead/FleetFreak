<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$broadcastMessage->company_id}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
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
                <label for="title">Title</label>
                <input type="text" required placeholder="Title" name="title" class="form-control {{($errors->has('title') ? ' is-invalid' : '')}}" id="title" value="{{$broadcastMessage->title}}">
                {!! $errors->first('title', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="message">Message</label>
                <input type="text" required placeholder="Message" name="message" class="form-control {{($errors->has('message') ? ' is-invalid' : '')}}" id="message" value="{{$broadcastMessage->message}}">
                {!! $errors->first('message', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" required placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$broadcastMessage->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="broadcast_type">Broadcast Type</label>
                <select name="broadcast_type" class="form-control {{ $errors->has('broadcast_type') ? 'is-invalid' : '' }}" id="broadcast_type">
                    <option value="" disabled selected>Select Type</option>
                    <option selected value="immediate" {{ $broadcastMessage->broadcast_type == 'immediate' ? 'selected' : '' }}>Immediate</option>
                </select>
                {!! $errors->first('broadcast_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="broadcast_frequency">Broadcast Frequency</label>
                <select name="broadcast_frequency" class="form-control {{ $errors->has('broadcast_frequency') ? 'is-invalid' : '' }}" id="broadcast_frequency">
                    <option value="" disabled selected>Select Frequency</option>
                    <option value="just_once" {{ $broadcastMessage->broadcast_frequency == 'just_once' ? 'selected' : '' }}>Just Once</option>
                    <option value="until_acknowledge" {{ $broadcastMessage->broadcast_frequency == 'until_acknowledge' ? 'selected' : '' }}>Until Acknowledge</option>
                    <option value="until_expiration" {{ $broadcastMessage->broadcast_frequency == 'until_expiration' ? 'selected' : '' }}>Until Expiration</option>
                    <option value="until_expiration_acknowledge" {{ $broadcastMessage->broadcast_frequency == 'until_expiration_acknowledge' ? 'selected' : '' }}>Until Expiration Acknowledge</option>
                </select>
                {!! $errors->first('broadcast_frequency', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="expired_date">Expired Date</label>
                <input type="date" placeholder="Expired Date" name="expired_date" class="form-control {{($errors->has('expired_date') ? ' is-invalid' : '')}}" id="expired_date" value="{{$broadcastMessage->expired_date}}">
                {!! $errors->first('expired_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6 my-3 ">
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="expired" value='0'>
                    <input type="checkbox" name="expired" class="form-check-input {{($errors->has('expired') ? ' is-invalid' : '')}}" id="expired" value="1">
                    <label class="form-check-label" for="expired">Expired</label>
                </div>
                {!! $errors->first('expired', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6 my-3">
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="published" value='0'>
                    <input type="checkbox" name="published" class="form-check-input {{($errors->has('published') ? ' is-invalid' : '')}}" id="published" value="1">
                    <label class="form-check-label" for="published">Published</label>
                </div>
                {!! $errors->first('published', '<div class="invalid-feedback">:message</div>') !!}
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
    $(document).ready(function() {
        $
    });
</script>
