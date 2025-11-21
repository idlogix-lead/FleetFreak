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
                        <span class="card-title">{{ __('Update') }} Product Type</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('product-types.update', $productType->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('product-type.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
