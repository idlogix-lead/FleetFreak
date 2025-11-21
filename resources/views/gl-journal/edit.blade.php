@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <h4 class="p-4">
                        <span class="card-title">
                            <div class="state-wrapper float-end">
                                {{-- <span>State:</span> --}}
                                <span id='state'>Saved</span>
                            </div>
                            {{ __('Update') }} Gl Journal
                        </span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" id="gljournal_form" action="{{ route('gl-journals.update', $glJournal->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('gl-journal.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
