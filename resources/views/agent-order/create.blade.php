@extends('layouts.app')

@section('wrapper')
<style>
    h4{
        color: black !important;
        /* background-color: #0d60af; */
    }
    .card-default{
        /* background-color: #0d60af; */
        background-color: white;
        /* background: linear-gradient(90deg, #0d60af 0%, #328bd1 100%); */

        border-radius: 20px;
    }

</style>
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    {{-- <h4 style="margin-bottom: -20px;" class="p-4">
                        <span class="card-title"><svg width="23" height="23" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>{{ __(' Create') }} Order</span> --}}


                        {{-- BTN FOR ADD DETAILS --}}


                    {{-- <button style=" border-radius:5px;" type="button" class="btn-sm btn-primary float-end" id="toggleIcon">
                        <!-- SVG Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        <!-- Text next to the icon -->
                        <span style="margin-left: 8px;">Add Details</span>
                    </button> --}}
                    {{-- <br>
                        <p style="font-size:18px; margin-top:20px; margin-bottom:0px; color: white;"><i>Find the best services for your bus travel</i></p> --}}

                    {{-- </h4> --}}

                    <div class="card-body">
                        <form method="POST" id="orderForm" action="{{ route('agentorders.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                                                                                         @include('agent-order.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>


    </section>

    {{-- customer modal --}}
    @include('order.partials.partner_modal')



@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let isFormDirty = false;

    // Mark form as dirty when input fields are changed
    const form = document.getElementById('orderForm');
    if (form) {
        form.addEventListener('input', () => {
            isFormDirty = true;
        });

        // Reset the dirty flag on form submission
        form.addEventListener('submit', () => {
            isFormDirty = false;
        });
    }

    // Add a confirmation message before the user navigates away
    window.addEventListener('beforeunload', function (e) {
        if (isFormDirty) {
            // Standard confirmation message
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave this page?';
        }
    });

    // Confirmation on tab/module change
    // document.querySelectorAll('a').forEach(anchor => {
    //     anchor.addEventListener('click', function (event) {
    //         if (isFormDirty) {
    //             const leave = confirm('You have unsaved changes. Do you really want to leave?');
    //             if (!leave) {
    //                 event.preventDefault();
    //             }
    //         }
    //     });
    // });
});

</script>

