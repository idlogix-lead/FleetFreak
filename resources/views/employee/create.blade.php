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
                        <span class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                          </svg>{{ __(' Create') }} Employee</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('employees.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('employee.form')

                            <div class="box-footer mt20">
                                @php
                                $model=[
                                    'notify_btn' => "Save",
                                    'function' => "Save",
                                    'body' => 'Please Confirm do you realy want to Save?',
                                    'btn-color' => 'primary',
                                    'float' => "end mt-2",
                                    'id' => "save"
                                    ];
                                @endphp
                                @include('partials.modal', ['data'=>$model])
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
