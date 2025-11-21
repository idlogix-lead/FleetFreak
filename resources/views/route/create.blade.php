@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <h4 class="p-4">
                        <span class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" fill="currentColor" class="bi bi-signpost" viewBox="0 0 16 16">
                            <path d="M7 1.414V4H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h5v6h2v-6h3.532a1 1 0 0 0 .768-.36l1.933-2.32a.5.5 0 0 0 0-.64L13.3 4.36a1 1 0 0 0-.768-.36H9V1.414a1 1 0 0 0-2 0M12.532 5l1.666 2-1.666 2H2V5z"/>
                          </svg>{{ __(' Create') }} Route</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('routes.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('route.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
