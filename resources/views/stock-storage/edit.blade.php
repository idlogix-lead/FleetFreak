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
                    <form method="POST" action="{{ route('stock-storages.update', $stockStorage->id) }}"  role="form" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf
                        <h6 class="p-4">
                            <span class="card-title"> Stock Storage</span>
                        </h6>
                        <div class="card-body">
                        

                                @include('stock-storage.form')

                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
