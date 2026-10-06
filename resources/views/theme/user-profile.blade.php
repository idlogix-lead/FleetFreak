		@extends("layouts.app")
		@section("wrapper")
            {{-- <div class="page-wrapper"> --}}
                <div class="page-content">
                    <!--breadcrumb-->
                    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                        <div class="breadcrumb-title pe-3">User Profile</div>
                        <div class="ps-3">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 p-0">
                                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                                </ol>
                            </nav>
                        </div>
                        {{-- <div class="ms-auto">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary">Settings</button>
                                <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">	<a class="dropdown-item" href="javascript:;">Action</a>
                                    <a class="dropdown-item" href="javascript:;">Another action</a>
                                    <a class="dropdown-item" href="javascript:;">Something else here</a>
                                    <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <!--end breadcrumb-->
                    <div class="container">
                        <div class="main-body">
                            {{-- <div class="row">
                                <div class="col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                {{-- @if ($user->image)
                                                <img src="{{ asset('storage/'.$user->image) }}" alt="User Image" class="rounded-circle p-1 bg-primary" width="110">
                                                @else
                                                <img src="{{ asset('storage/profile_images/default/default.jpg') }}" alt="Default Image" class="rounded-circle p-1 bg-primary" width="110">
                                                @endif --}}

                                                 {{-- <img src="data:image/jpeg;base64,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" class="user-img" alt="user avatar">

                                                <div class="mt-3">
                                                    <h4>{{$user->name}}</h4>
                                                    <p class="text-secondary mb-1">{{$user->type}}</p>
                                                    <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                                                    {{-- <button class="btn btn-primary">Follow</button>
                                                    <button class="btn btn-outline-primary">Message</button> --}}
                                                {{-- </div> --}}
                                            {{-- </div> --}}
                                            {{-- <hr class="my-4" />
                                            <ul class="list-group list-group-flush"> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Website</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->website ? $user->socialprofile->website : 'your website'}}</span>
                                                    @else
                                                    'your website'
                                                    @endif

                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github me-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>Github</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->github ? $user->socialprofile->github :'your github'}}</span>
                                                    @else
                                                    'your github'
                                                    @endif
                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter me-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->twitter ? $user->socialprofile->twitter :'your twitter'}}</span>
                                                    @else
                                                    'your twitter'
                                                    @endif
                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->instagram ? $user->socialprofile->instagram : 'your instagram'}}</span>
                                                    @else
                                                    'your instagram'
                                                    @endif
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->facebook ? $user->socialprofile->facebook :'your facebook'}}</span>
                                                    @else
                                                    'your facebook'
                                                    @endif
                                                </li> --}}
                                            {{-- </ul>
                                        </div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-8">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('user-profile.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Full Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Phone No</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="number" class="form-control" name="phone_no" value="{{ $user->phone_no }}" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">CNIC</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="number" class="form-control" name="CNIC" value="{{ $user->CNIC }}" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Description</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <textarea class="form-control" rows="4" name="description">{{ $user->description }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Image</h6>
                                                    </div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="file" class="form-control"  name="image" accept="image/*"/>
                                                        <p>{{ $user->image ? basename($user->image) : 'No file chosen' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 text-secondary">
                                                        <input type="submit" class="btn btn-primary px-4" value="Save Changes" />
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div> --}}

                                    {{-- <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="d-flex align-items-center mb-3">Project Status</h5>
                                                    <p>Web Design</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Website Markup</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>One Page</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Mobile Template</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Backend API</p>
                                                    <div class="progress" style="height: 5px">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                {{-- </div> --}}
                            {{-- </div>  --}}








                            {{-- changes --}}
                             <div class="row">
                                <div class="col-lg-10 ms-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex flex-column align-items-center text-center">
                                                {{-- @if ($user->image)
                                                <img src="{{ asset('storage/'.$user->image) }}" alt="User Image" class="rounded-circle p-1 bg-primary" width="110">
                                                @else
                                                <img src="{{ asset('storage/profile_images/default/default.jpg') }}" alt="Default Image" class="rounded-circle p-1 bg-primary" width="110">
                                                @endif --}}

                                                 <img src="data:image/jpeg;base64,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" class="user-img  border border-primary"" alt="user avatar">

                                                <div class="mt-3">
                                                    <h4>{{$user->name}}</h4>
                                                    <p class="text-secondary mb-1">{{$user->type}}</p>
                                                    <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                                                    {{-- <button class="btn btn-primary">Follow</button>
                                                    <button class="btn btn-outline-primary">Message</button> --}}
                                                </div>
                                            </div>
                                            <hr class="my-4" />
                                            <ul class="list-group list-group-flush">
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2 icon-inline"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>Website</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->website ? $user->socialprofile->website : 'your website'}}</span>
                                                    @else
                                                    'your website'
                                                    @endif

                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-github me-2 icon-inline"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>Github</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->github ? $user->socialprofile->github :'your github'}}</span>
                                                    @else
                                                    'your github'
                                                    @endif
                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-twitter me-2 icon-inline text-info"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>Twitter</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->twitter ? $user->socialprofile->twitter :'your twitter'}}</span>
                                                    @else
                                                    'your twitter'
                                                    @endif
                                                </li> --}}
                                                {{-- <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-instagram me-2 icon-inline text-danger"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>Instagram</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->instagram ? $user->socialprofile->instagram : 'your instagram'}}</span>
                                                    @else
                                                    'your instagram'
                                                    @endif
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                                                    <h6 class="mb-0"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-facebook me-2 icon-inline text-primary"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>Facebook</h6>
                                                    @if ($user->socialprofile)
                                                    <span class="text-secondary">{{$user->socialprofile->facebook ? $user->socialprofile->facebook :'your facebook'}}</span>
                                                    @else
                                                    'your facebook'
                                                    @endif
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 ms-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="POST" action="{{ route('user-profile.update') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Full Name</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Email</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" />
                                                    </div>
                                                </div>

                                                {{-- <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Phone No</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <input type="number" class="form-control" name="phone_no" value="{{ $user->phone_no }}" />
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">CNIC</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <input type="number" class="form-control" name="CNIC" value="{{ $user->CNIC }}" />
                                                    </div>
                                                </div> --}}
                                                {{-- <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Description</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <textarea class="form-control" rows="4" name="description">{{ $user->description }}</textarea>
                                                    </div>
                                                </div> --}}
                                                <div class="row mb-3">
                                                    <div class="col-sm-3">
                                                        <h6 class="mb-0">Image</h6>
                                                    </div>
                                                    <div class="col-sm-9 col-md-8 text-secondary">
                                                        <input type="file" class="form-control"  name="image" accept="image/*"/>
                                                        <p>{{ $user->image ? basename($user->image) : 'No file chosen' }}</p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9 col-md-8  d-flex justify-content-center text-secondary ">
                                                        <input type="submit" class="btn btn-sm btn-primary float-end" value="Save Changes" />
                                                    </div>
                                                </div>
                                            </form>
                                            <div style="margin-top: -30px;" class="row">

                                                    <form action="{{ route('user-profile.password') }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        @php
                                                        // $icon =
                                                        // '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>';
                                                        $model=[
                                                            'notify_btn' => "Password Change",
                                                            'function' => "Password Change",
                                                            'body' => 'Please Enter Your Password '.$user->name.' ?',
                                                            'btn-color' => 'success',
                                                            'float' => "end",
                                                            'id' => "changepassword-$user->id",
                                                            'current_password' => true,
                                                            ];
                                                        @endphp

                                                        @include('user.partials.profile-password', ['data'=>$model])
                                                    </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                    {{-- <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="d-flex align-items-center mb-3">Project Status</h5>
                                                    <p>Web Design</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Website Markup</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>One Page</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Mobile Template</p>
                                                    <div class="progress mb-3" style="height: 5px">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 55%" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                    <p>Backend API</p>
                                                    <div class="progress" style="height: 5px">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 66%" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </script>
		@endsection



