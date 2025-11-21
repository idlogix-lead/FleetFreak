 @extends('layouts.app')
 @section('style')
     <link href="assets/plugins/input-tags/css/tagsinput.css" rel="stylesheet" />
 @endsection
 @section('wrapper')
     <div class="page-wrapper">
         <div class="page-content">
             <!--breadcrumb-->
             <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                 <div class="breadcrumb-title pe-3">Forms</div>
                 <div class="ps-3">
                     <nav aria-label="breadcrumb">
                         <ol class="breadcrumb mb-0 p-0">
                             <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                             </li>
                             <li class="breadcrumb-item active" aria-current="page">Form Elements</li>
                         </ol>
                     </nav>
                 </div>
                 <div class="ms-auto">
                     <div class="btn-group">
                         <button type="button" class="btn btn-primary">Settings</button>
                         <button type="button"
                             class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split"
                             data-bs-toggle="dropdown"> <span class="visually-hidden">Toggle Dropdown</span>
                         </button>
                         <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end"> <a class="dropdown-item"
                                 href="javascript:;">Action</a>
                             <a class="dropdown-item" href="javascript:;">Another action</a>
                             <a class="dropdown-item" href="javascript:;">Something else here</a>
                             <div class="dropdown-divider"></div> <a class="dropdown-item" href="javascript:;">Separated
                                 link</a>
                         </div>
                     </div>
                 </div>
             </div>
             <!--end breadcrumb-->
             <div class="row">
                 <div class="col-xl-9 mx-auto">
                     <h6 class="mb-0 text-uppercase">Complaints form</h6>
                     <hr />
                     <div class="card">
                         <div class="card-body">
                             <form action="">
                                 <label for="name">Enter Name</label>
                                 <input type="text" class="form-control mb-3" id="name" aria-describedby="name"
                                     placeholder="Enter Name">
                                 <label for="exampleInputEmail1">Email address</label>
                                 <input type="email" class="form-control mb-3" id="exampleInputEmail1"
                                     aria-describedby="name" placeholder="Enter email">
                                 <label for="address">Enter Address</label>
                                 <input type="text" class="form-control mb-3" id="address" aria-describedby="address"
                                     placeholder="Enter Address">
                                 <label for="city">Enter City</label>
                                 <input type="text" class="form-control mb-3" id="city" placeholder="Enter City">
                                 <label for="number">Enter Contact Number</label>
                                 <input type="tel" class="form-control mb-3" id="contact"
                                     placeholder="Contact Number">
                                 <button type="submit" class="btn btn-primary mb-3">Next</button>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>
             <!--end row-->
         </div>
     </div>
 @endsection

 @section('script')
     <script src="assets/plugins/input-tags/js/tagsinput.js"></script>
 @endsection
