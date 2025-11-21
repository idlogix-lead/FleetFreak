@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Sale</span>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('sales.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('sale.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
