
<script>
  
    $(document).ready(function(){

        $('.modal').on('hidden.bs.modal', function (e) {
            $("form").valid();
        });
 
    });

    // {{-- to check password equal to confirm password --}}

    
</script> 

{{-- <button type="button" class="btn-success btn-sm  float-{{ $data['float'] }} t modd" data-bs-toggle="modal"
    data-bs-target="#exampleModal{{ $data['id'] }}" id="btn_{{ $data['id'] }}">
{!! $data['notify_btn'] !!}
</button> --}}
<button type="button" class="btn btn-{{ $data['btn-color'] }} btn-sm float-{{ $data['float'] }}  t modd " data-bs-toggle="modal"
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
                @if ($data['btn-color'] == 'danger')    
                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>
                @endif

            </div>
            <div class="text-center">
                <br>
                <h5 class="modal-title" id="exampleModalLabel">{{ $data['function'] }} !</h5>
            </div>
            <br>
            <span class="text-center px-2" style="overflow: hidden;white-space: pre-line;"> {{ $data['body'] }} </span>
            <div class="input-group mb-3  ">
                <input type="password" style="margin-left: 20px; margin-right: 20px; margin-top: 4px;" name="password" id="password" class="form-control" placeholder="New Password">
            </div>
            <div class="input-group mb-3 ">
                <input type="password" style="margin-left: 20px; margin-right: 20px;" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password">
            </div>
           
            {{-- <div id="passwordError" style="color: red;"></div> --}}
           
            <div class="text-center my-3" style="overflow: hidden;">
                <button type="button" class="btn btn-outline-info" data-bs-dismiss="modal">Cancel</button>
                <button autofocus type="submit" class="btn btn-outline-{{ $data['btn-color'] }} ml-1 sub" id='confirm_{{ $data['id'] }}'>Confirm
                    {{ $data['function'] }} 
                </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

           

