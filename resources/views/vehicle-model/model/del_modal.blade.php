{{-- <audio class='aud' id="myAudio">
    <source src="{{ asset('/audio/warning.ogg') }}" type="audio/ogg">
    <source src="{{ asset('/audio/warning.mp3') }}" type="audio/mpeg">
</audio> --}}
<script>
    // var x = document.getElementById("myAudio");
    $(document).ready(function(){


        $('.modal').on('hidden.bs.modal', function (e) {
            $("form").valid();
        });

    });
</script>

<button style="padding: 0px; 0px;" type="button" class="btn btn-sm float-{{ $data['float'] }}  t modd mx-1  " data-bs-toggle="modal"
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
    // $(document).ready(function(){
    //     $('#confirm_{{ $data['id'] }}').click(function(e) {
    //         e.preventDefault();

    //         // Check if the data is master data
    //         var isMasterData = true; // Replace this with your actual check
    //         if (isMasterData) {
    //             msgboxbox.show('You Cannot Delete Master Data!','error', null);
    //             // Optionally, close the modal
    //             $('#exampleModal{{ $data['id'] }}').modal('hide');
    //         } else {
    //             // Proceed with form submission
    //             $(this).closest('form').submit();
    //         }
    //     });
    // });
    </script>




















