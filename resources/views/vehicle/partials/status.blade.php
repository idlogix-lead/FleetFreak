
<script>

    // $(document).ready(function(){

    //     $('.modal').on('hidden.bs.modal', function (e) {
    //         $("form").valid();
    //     });
    // });
</script>
{{-- <button type="button" title = "status" class="btn btn-outline-status float-{{ $data['float'] }} btn-sm t modd my-3 " data-bs-toggle="modal"
    data-bs-target="#exampleModal{{ $data['id'] }}" id="btn_{{ $data['id'] }}">
{!! $data['notify_btn'] !!}
</button> --}}

<button type="button" title="status" class="float-{{ $data['float'] }} t modd my-3 border-0 p-0" style="color: #696b6c"
    data-bs-toggle="modal" data-bs-target="#exampleModal{{ $data['id'] }}"
    id="btn_{{ $data['id'] }}">
    {!! $data['notify_btn'] !!}
</button>

<div class="modal fade" style='top:25%;' id="exampleModal{{ $data['id'] }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content bgdark">

            <div class="text-center">
                <button type="button" class="btn-close m-2 float-end" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">

                    </span>
                </button>

            </div>
            <div class="text-center" >
                {{-- @if ($data['btn-color'] == 'danger')     --}}
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#640D5F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>

                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg> --}}
                {{-- @endif --}}

            </div>
            <div class="text-center">
                <br>
                <h5 class="modal-title" id="exampleModalLabel">{{ $data['function'] }} !</h5>
            </div>
            <br>
            <span class="text-center px-2" style="overflow: hidden;white-space: pre-line;"> {{ $data['body'] }} </span>
            <div class="form-group mx-3">
                @if($vehicle->is_status== 'active')
                <div class="form-check">
                    <input type="radio" name="status[{{ $index }}]" value="sold" class="form-check-input sold-radio" id="soldRadio_{{ $index }}">
                    <label class="form-check-label" style="margin-left: -90%;" for="soldRadio_{{ $index }}">Sold</label>
                </div>
                <div class="form-check">
                    <input type="radio" name="status[{{ $index }}]" value="inactive" class="form-check-input inactive-radio" id="inactiveRadio_{{ $index }}" data-index="{{ $index }}">
                    <label class="form-check-label"  style="margin-left: -85%;" for="inactiveRadio_{{ $index }}">Inactive</label>
                </div>
                <div id="reasonField_{{ $index }}" class="form-group mx-2 reason-field" style="display: {{ $vehicle->is_status == 'inactive' ? 'block' : 'none' }}">
                    <label for="reason_{{ $index }}">Reason for Inactivation:</label>
                    <input type="text" placeholder="Enter Your Reason" name="reason[{{ $index }}]" id="reason_{{ $index }}" class="form-control">
                </div>
                @endif
                @if($vehicle->is_status== 'inactive')
                <div class="form-check">
                    <input type="radio" name="status[{{ $index }}]" value="sold" class="form-check-input sold-radio" id="soldRadio_{{ $index }}">
                    <label class="form-check-label" style="margin-left: -90%;" for="soldRadio_{{ $index }}">Sold</label>
                </div>

                <div class="form-check">
                    <input type="radio" name="status[{{ $index }}]" value="active" class="form-check-input active-radio" id="activeRadio_{{ $index }}">
                    <label class="form-check-label" style="margin-left: -85%;" for="activeRadio_{{ $index }}">Active</label>
                </div>
                @endif
            </div>



            <div class="text-center my-3" style="overflow: hidden;">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button autofocus type="submit" class="btn btn-primary ml-1 sub" id='confirm_{{ $data['id'] }}'>Confirm
                    {{ $data['function'] }}
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.inactive-radio').change(function() {
            const index = $(this).data('index');
            $('#reasonField_' + index).show();
        });

        $('.sold-radio, .active-radio').change(function() {
            const index = $(this).closest('.form-group').find('.inactive-radio').data('index');
            $('#reasonField_' + index).hide();
        });
    });
</script>


