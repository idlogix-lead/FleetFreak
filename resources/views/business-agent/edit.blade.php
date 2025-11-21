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
                        <span class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                          </svg>{{ __(' Update') }} Business</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('business_agents.update', $partner->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('business-agent.form')

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
               {{-- display only business created users --}}
                    {{-- <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="pb-4">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
            
                                            <h3 class="card-title">
                                                {{ __(' All Business Users') }}
                                            </h3>
        
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id='user' class="table table-hover">
                                            <thead class="thead">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Image</th>
                                               
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role Name</th>
                                                 
            
                                                    <th>Actions</th>
                                                </tr>
                                            </thead> --}}
                                            {{-- <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ ++$i }}</td>
                                                        
                                                        <td> 
                                                            <div class = "d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret">
                                                            <img src="{{ asset('storage/'.$user->image) }}" class="user-img" alt="user avatar">
                                                         
                                                            </div>
                                                        </td>
                                                        
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->role->name??'' }}</td>
                                                        
            
                                                        <td style='display:flex;'>
                                                            <form action="{{ route('users.destroy',$user->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
            
                                                                @php 
                                                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                                $model=[
                                                                    'notify_btn' => $icon,
                                                                    'function' => "Delete",
                                                                    'body' => 'Please Confirm do you realy want to Delete '.$user->id.' ?',
                                                                    'btn-color' => 'danger',
                                                                    'float' => "end",
                                                                    'id' => "del-$user->id"
                                                                    ];
                                                                @endphp
                                                                
                                                                @include('partials.modal', ['data'=>$model])
                                                            </form>
                                                            
                                                            <a class="btn btn-outline-primary float-right ms-1 my-3 " href="{{ route('users.edit',$user->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                            </a>
                                                            <a class="btn btn-outline-success float-right ms-1 mx-1 my-3   " href="{{ route('users.show',$user->id) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                            </a>
                                                            
                                                            <form action="{{ route('users.change-password',$user->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                @php 
                                                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-key"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"></path></svg>';
                                                                $model=[
                                                                    'notify_btn' => $icon,
                                                                    'function' => "Password Change",
                                                                    'body' => 'Please Enter Your Password '.$user->name.' ?',
                                                                    'btn-color' => 'danger',
                                                                    'float' => "end",
                                                                    'id' => "changepassword-$user->id"
                                                                    ];
                                                                @endphp
                                                                
                                                                @include('user.partials.password', ['data'=>$model])
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody> --}}
                                        </table>
                                        {{-- {!! $users->links() !!} --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                {{-- --------------------------------------------------------- --}}
                {{-- <div class = "card card-default">
                    <div class="card-body">
                        <div class="box box-info padding-1">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="partner_type" >All Business Users</label>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
@endsection
