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
                        <span class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>{{ __(' Create') }}Pending Orders</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('pending_orders.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('pending_order.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>

        
    </section>

    {{-- customer modal --}}
    @include('order.partials.partner_modal')
    
    

@endsection

