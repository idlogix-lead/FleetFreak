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
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Role Module</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('role_modules.update', $roleModule->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf
                            @php
                                $edit = true;
                            @endphp
                            @include('role-module.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
