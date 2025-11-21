@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    @php
        $disabled = '';
        if ($activity->document_status == 'completed') {
            $disabled = 'disabled';
        }
    @endphp
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                <div class="card card-default">
                    <form method="POST" action="{{ route('purchase_invoices.update', $activity->id) }}"  role="form" enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf
                        <h6 class="p-4">
                            <span class="card-title"> Purchase Invoice</span>
                            @if (!$disabled)
                                @php
                                    $model = [
                                        'notify_btn' => 'Draft',
                                        'function' => 'Save',
                                        'body' => 'Please Confirm do you realy want to Draft?',
                                        'btn-color' => 'primary',
                                        'float' => 'end',
                                        'id' => 'draft',
                                    ];

                                    // $model2 = [
                                    //     'notify_btn' => 'Completed',
                                    //     'function' => 'Save',
                                    //     'body' => 'Please Confirm do you realy want to Save?',
                                    //     'btn-color' => 'success',
                                    //     'float' => 'end',
                                    //     'id' => 'complete',
                                    // ];
                                @endphp
                                @include('partials.new-modal-btn', ['data' => $model])
                                {{-- @include('partials.new-modal-btn', ['data' => $model2]) --}}
                            @endif
                        </h6>
                        <div class="card-body">
                        

                                @include('purchase-invoice.form')

                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
