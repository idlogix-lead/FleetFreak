{{-- <audio class='aud' id="myAudio">
    <source src="{{ asset('/audio/warning.ogg') }}" type="audio/ogg">
    <source src="{{ asset('/audio/warning.mp3') }}" type="audio/mpeg">
</audio> --}}
<script>
    // var x = document.getElementById("myAudio");
    $(document).ready(function(){

        // $('.t').click(function(){
        //     x.play();
        // });
        // $('.sub').click(function(){
        //     $('.modal').modal('hide');
        // });
        $('.modal').on('hidden.bs.modal', function (e) {
            $("form").valid();
        });
        // $("form").submit(function(){
        //     $('.loaded .loader-wrapper').css('visibility','inherit');
        //     $('.loaded .loader-wrapper').css('opacity','0.7');
        // });
        // var stscheck = true;
        // $(document).on('keydown',function(e){
        //     var code = e.keyCode || e.which;
        //     var alt = e.altKey;
        //     // alt + s
        //     var func = "{{ $data['function'] }}";
        //     if(!$('#partial').val() && func != 'Delete'  && func != 'Recover' && alt && code == 83){
        //             x.play();
        //             $('.modal').modal('show');
        //     }
        //     if(alt && code == 67){
        //         $('.modal').modal('hide');
        //     }
        // });
    });
</script>

<button type="button" class="btn-sm btn-outline-{{ $data['btn-color'] }} float-{{ $data['float'] }}  t modd" data-bs-toggle="modal"
    data-bs-target="#exampleModal{{ $data['id'] }}" id="btn_{{ $data['id'] }}">
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
                @if ($data['btn-color'] == 'info' or $data['btn-color'] == 'primary' or $data['btn-color'] == 'success')
                    <svg  width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-circle"><circle  class='text-{{$data['btn-color']}}' cx="12" cy="12" r="10"></circle><line  class='text-{{$data['btn-color']}}' x1="12" y1="8" x2="12" y2="12"></line><line  class='text-{{$data['btn-color']}}' x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                @elseif ($data['btn-color'] == 'warning')
                    <svg  width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-octagon"><polygon  class='text-{{$data['btn-color']}}' points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line  class='text-{{$data['btn-color']}}' x1="12" y1="8" x2="12" y2="12"></line><line class='text-{{$data['btn-color']}}' x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                @elseif ($data['btn-color'] == 'danger')
                    <svg  width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle class='text-{{$data['btn-color']}}' cx="12" cy="12" r="10"></circle><line  class='text-{{$data['btn-color']}}' x1="15" y1="9" x2="9" y2="15"></line><line  class='text-{{$data['btn-color']}}' x1="9" y1="9" x2="15" y2="15"></line></svg>
                @endif

            </div>
            <div class="text-center">
                <br>
                <h5 class="modal-title" id="exampleModalLabel">{{ $data['function'] }} !</h5>
            </div>
            <br>
            <span class="text-center px-2" style="overflow: hidden;white-space: pre-line;"> {{ $data['body'] }} </span>
            <div class="text-center my-3" style="overflow: hidden;">

                <div id="reasonField" class="form-group mx-2 " >
                    <label for="reason">Reason For UnApproving Order:</label>
                    <input type="text" placeholder="Enter Your Reason" name="reason" id="reason" class="form-control" >
                </div>

                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button autofocus type="submit" class="btn btn-primary ml-1 sub" id='confirm_{{ $data['id'] }}'>Confirm
                    {{ $data['function'] }}
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


























  <!-- Modal -->
{{-- <div class="modal fade" id="exampleModal{{ $data['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            ...
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
    </div>
</div> --}}
