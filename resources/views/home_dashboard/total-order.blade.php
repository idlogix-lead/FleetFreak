@extends('layouts.app')
@section('wrapper')
    {{-- @if (isset($breadcrumbs)) --}}
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h4 class="card-title"><svg width="26" height="26" fill= "currentColor"xmlns="http://www.w3.org/2000/svg"
                fill-rule="evenodd" clip-rule="evenodd">
                <path
                    d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z" />
            </svg>
            {{ $ordertype }}

        </h4>

        <div class="float-right mx-2">
            <a @if ($ordertype == 'total-orders') href="{{ route('export.orders') }}" @elseif($ordertype == 'pending-orders') href="{{ route('export.pending.orders') }}" @elseif($ordertype == 'approved-orders') href="{{ route('export.approved.orders') }}" @elseif ($ordertype == 'incomplete-orders') href="{{ route('export.incomplete.orders') }}" @elseif ($ordertype == 'unapproved-orders') href="{{ route('export.unapproved.orders') }}"  @elseif ($ordertype == 'cancelled-orders') href="{{ route('export.cancel.orders') }}" @elseif ($ordertype == 'completed-orders') href="{{ route('export.completed.orders') }}" @endif
                class="btn btn-outline-primary float-right" data-placement="left">


{{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="80px" height="80px"><g fill-opacity="0.70196" fill="#0000ff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(3.2,3.2)"><path d="M45,9.79297l-36,6.82031v46.77344l36,6.82031v-7.20703h24v-46h-24zM43,12.20703v55.58594l-32,-6.0625v-43.46094zM40,15c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,19c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM45,19h22v42h-22v-8h4v-2h-4v-6h4v-2h-4v-6h4v-2h-4v-6h4v-2h-4zM40,23c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,27c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,27v2h11v-2zM17.45313,31l5.26953,9.03516l-5.75781,9.03125h4.84766l3.125,-5.83203c0.21875,-0.5625 0.35938,-0.98437 0.41797,-1.26172h0.05078c0.125,0.58984 0.24609,0.99609 0.36328,1.21484l3.11328,5.87891h4.82422l-5.56641,-9.10937l5.41797,-8.95703h-4.53906l-2.875,5.36719c-0.27344,0.69531 -0.46484,1.22266 -0.56641,1.57422h-0.04687c-0.16016,-0.58594 -0.33984,-1.09375 -0.54297,-1.52344l-2.58203,-5.41797zM40,31c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,35c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,35v2h11v-2zM40,39c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,43c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,43v2h11v-2zM40,47c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,51c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,51v2h11v-2zM40,55c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,59c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,63c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1z"></path></g></g></svg> --}}
                    {{-- <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line> --}}
                {{-- </svg>
                {{-- {{ __('Create New') }} --}}
                 {{-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-excel-fill" viewBox="0 0 16 16">
                   <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64"/>
                 </svg> --}}

                 <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                   <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                     <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                 </svg>
            </a>
        </div>
    </div>
    <br>
    {{-- @include('layouts.partials.breadcrumb',compact('breadcrumbs')) --}}
    {{-- @endif --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title"><svg width="26" height="26" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>
                                    {{ __(' Order') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('orders.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                                        <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    {{ __('Create New') }}
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsive">
                            <table id='order' class="table table-hover border text-center table-montserrat">
                                <thead class="thead table-light t-head-clr">
                                    <tr>
                                        <th>No #</th>

                                        <th>Order No <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-down">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg></span></th>
                                        <th>Customer Partner<span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-down">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg></span></th>
                                        <th>Agent <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-down">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg></span></th>
                                        <th>Driver <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-arrow-down">
                                                <line x1="12" y1="5" x2="12" y2="19">
                                                </line>
                                                <polyline points="19 12 12 19 5 12"></polyline>
                                            </svg></span></th>
                                        <th>Status <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-down">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg></span></th>
                                        {{-- <th>Actions <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-arrow-down">
                                                    <line x1="12" y1="5" x2="12" y2="19">
                                                    </line>
                                                    <polyline points="19 12 12 19 5 12"></polyline>
                                                </svg></span></th> --}}
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$i }}</td>

                                            <td>{{ $order->id }}</td>
                                            <td>{{ Str::title($order->order->partner_customer->name ?? null) }}</td>
                                            <td>{{ Str::title($order->order->partner_business->company_name ??$order->driver->name?? null ) }}</td>
                                            <td>{{ Str::title($order->driver->name ??'Not Assigned Yet') }}</td>

                                            <td>@php
                                                switch ($order->status) {
                                                    case 'completed':
                                                        $badgeClass = 'success';
                                                        break;
                                                    case 'cancelled':
                                                        $badgeClass = 'danger';
                                                        break;
                                                    case 'draft':
                                                        $badgeClass = 'primary';
                                                        break;
                                                    case 'unapproved':
                                                        $badgeClass = 'warning';
                                                        break;
                                                    default:
                                                        $badgeClass = 'primary';
                                                }
                                            @endphp
                                                <span
                                                    class="badge bg-light-{{ $badgeClass }} text-{{ $badgeClass }}  ">{{ Str::title($order->status) }}</span>
                                            </td>

                                            {{-- <td style=''> --}}
                                            {{-- <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$order->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$order->id"
                                                        ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                                </form> --}}

                                            {{-- @if ($order->overall_status != 'cancelled' && $order->overall_status != 'completed')
                                                <a class="btn btn-outline-primary btn-sm float-right ms-1" href="{{ route('orders.edit',$order->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                @endif --}}

                                            {{-- <a class="btn btn-outline-success float-right ms-1" href="{{ route('orders.show',$order->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> --}}
                                            {{-- </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
