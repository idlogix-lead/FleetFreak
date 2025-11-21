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
                        <span class="card-title">{{ __('Update') }} {{$is_inspection?'Inspection':'Maintenance'}}</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ $is_inspection?route('inspections.update', $maintenance->id):route('maintenances.update', $maintenance->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf
                            @php
                                $edit = true;
                            @endphp
                            @include('maintenance.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
