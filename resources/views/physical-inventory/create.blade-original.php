@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <h6 class="p-4">
                        <span class="card-title">Inventory Move</span>
                    </h6>
                    <div class="card-body">
                        <form method="POST" action="{{ route('inventory_move.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('inventory-move.form')
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
