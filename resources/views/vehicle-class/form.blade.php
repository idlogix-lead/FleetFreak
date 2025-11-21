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
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$vehicleClass->name}}" required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
         </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" placeholder="Description" name="description"
                        class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description"
                        value="{{ $vehicleClass->description }}">
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="seats_allow">Seats Allow</label>
                    <input type="text" placeholder="seats_allow" name="seats_allow"
                        class="form-control {{ $errors->has('seats_allow') ? ' is-invalid' : '' }}" id="seats_allow"
                        value="{{ $vehicleClass->seats_allow }}" required>
                    {!! $errors->first('seats_allow', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bags_allow">Bags Allow</label>
                    <input type="text" placeholder="bags_allow" name="bags_allow"
                        class="form-control {{ $errors->has('bags_allow') ? ' is-invalid' : '' }}" id="bags_allow"
                        value="{{ $vehicleClass->bags_allow }}" required>
                    {!! $errors->first('bags_allow', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

        </div>
    </div>
    <div class="box-footer mt20">
        @php
            $model = [
                'notify_btn' => 'Save',
                'function' => 'Save',
                'body' => 'Please Confirm do you realy want to Save?',
                'btn-color' => 'primary',
                'float' => 'end mt-2',
                'id' => 'save',
            ];
        @endphp
        @include('partials.modal', ['data' => $model])
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#name').on('input', function() {
            var inputValue = $(this).val();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            // Limit to exactly 11 numbers
            // var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(numericValue);
        });

    $('#seats_allow').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
    $('#bags_allow').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });

    })
</script>
