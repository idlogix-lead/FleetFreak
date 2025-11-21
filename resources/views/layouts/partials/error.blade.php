@if ($errors->any())
    <audio autoplay>
        {{-- <source src="{{ asset('/audio/error.ogg') }}" type="audio/ogg">
        <source src="{{ asset('/audio/error.mp3') }}" type="audio/mpeg"> --}}
    </audio>
    @foreach ($errors->all() as $error)
        <script>
            msgboxbox.show("{{ $error }}",'error', null);
            //toastr.error("{{ $error }}");
        </script>
    @endforeach
@endif