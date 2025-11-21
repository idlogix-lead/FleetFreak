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
                        <span class="card-title">{{ __('Update') }} Payments</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('payments.update', $payment_headers->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('payment.form')
                            {{-- <div class="box-footer mt20">
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
                            </div> --}}

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
