@if(Session::has('success'))
    <audio autoplay>
        {{-- <source src="{{ asset('/audio/success.ogg') }}" type="audio/ogg">
        <source src="{{ asset('/audio/success.mp3') }}" type="audio/mpeg"> --}}
    </audio>
    @if(gettype(Session::get('success')) == 'string') 
        <script>
            msgboxbox.show("{{ Session::get('success') }}",'success', null);

            // toastr.success("{{ Session::get('success') }}");
        </script>
    @elseif(gettype(Session::get('success')) == 'array')
        @foreach (Session::get('success') as $s)
            <script>
                msgboxbox.show("{{ $s }}",'success',null);
            </script>
        @endforeach
    @endif
@endif
@if(Session::has('error'))
    <audio autoplay>
        {{-- <source src="{{ asset('/audio/error.ogg') }}" type="audio/ogg">
        <source src="{{ asset('/audio/error.mp3') }}" type="audio/mpeg"> --}}
    </audio>
    @if(gettype(Session::get('error')) == 'string') 
        <script>
            msgboxbox.show("{{ Session::get('error') }}",'error', null);
            // toastr.error("{{ Session::get('error') }}");
        </script>
    @elseif(gettype(Session::get('error')) == 'array')
         @foreach(Session::get('error') as $e)
            <script>
                msgboxbox.show("{{ $e }}",'error',null);
            </script>
        @endforeach
    @endif
@endif
@if(Session::has('warning'))
    <audio autoplay>
        {{-- <source src="{{ asset('/audio/warning.ogg') }}" type="audio/ogg">
        <source src="{{ asset('/audio/warning.mp3') }}" type="audio/mpeg"> --}}
    </audio>
    @if(gettype(Session::get('warning')) == 'string') 
        <script>
            msgboxbox.show("{{ Session::get('warning') }}",'warning', null);
            // toastr.warning("{{ Session::get('warning') }}");
        </script>
    @elseif(gettype(Session::get('warning')) == 'array')
        @foreach (Session::get('warning') as $w)
            <script>
                msgboxbox.show("{{ $w }}",'warning',null);
            </script>
        @endforeach
    @endif
@endif
@if(Session::has('info'))
    <audio autoplay>
        {{-- <source src="{{ asset('/audio/warning.ogg') }}" type="audio/ogg">
        <source src="{{ asset('/audio/warning.mp3') }}" type="audio/mpeg"> --}}
    </audio>
    @if(gettype(Session::get('info')) == 'string') 
        <script>
            msgboxbox.show("{{ Session::get('info') }}",'info', null);

            // toastr.warning("{{ Session::get('info') }}");
        </script>
    @elseif(gettype(Session::get('info')) == 'array')
        @foreach (Session::get('info') as $in)
            <script>
                msgboxbox.show("{{ $in }}",'info',null);
            </script>
        @endforeach
    @endif
@endif
