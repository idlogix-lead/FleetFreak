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
                <div class="card card-default">
                    <form method="POST" action="{{ route('purchase_orders.store') }}"  role="form" enctype="multipart/form-data">
                        @csrf
                        <h6 class="p-4">
                            <span class="card-title">Purhcase Order</span>
                            
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
                                {{-- <a class="float-end mx-2" style="color: currentColor;" href="javascript:void(0)" onclick="printOrderDetails({{ $activity->id }})" class=" ms-2" title="Print">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                                        <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                        <path d="M6 18H4a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2"></path>
                                        <rect x="6" y="14" width="12" height="8"></rect>
                                    </svg>
                                    
                                </a> --}}
                                {{-- @include('partials.new-modal-btn', ['data' => $model2]) --}}
                            @endif
                        </h6>
                        <div class="card-body">
                       

                            @include('purchase-order.form')
                            
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
