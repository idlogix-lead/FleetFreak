@extends('layouts.app')


@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Role Module</span>
                        </div>
                        <div class="float-right">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Role ModuleName</th>
                                        <th>Action</th>
                                        <th>Function</th>
                                        <th>Return</th>
                                        <th>Error Message</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <!-- Table body will be appended dynamically -->
                                   
                                    @php
                                    $counter = 1;
                                    @endphp
                                    @foreach ($roleModule->role_permission_type as $item)
                                    <tr>
                                        <td>{{ $counter++ }}</td>
                                        <td>{{ $roleModule->name }}</td>
                                        <td>{{$item->action}}</td>
                                        <td>{{$item->function}}</td>
                                        <td>{{$item->return_type}}
                                        
                                        </td>
                                        <td>{{$item->denial_msg}}</td>
                                       
                                    </tr>
                                    @endforeach
                                   
                
                                </tbody>
                            </table>
                            <a class="btn btn-primary" href="{{ route('role_modules.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ Str::title ($roleModule->name) }}
                          
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
