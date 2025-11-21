@extends('layouts.app')

@section('wrapper')
{{-- for th styling --}}
<style>
    th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="pb-0">
                            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>{{ __(' Filterations') }}</h3>
                            </div>
                            @php
                                $link = 'agent.rides_window';
                            @endphp
                            @include('agent-rides-status.partials.filter')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title">
                                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                  {{ $ordertype}}</h3>
                                  <div class="float-right mx-2">
                                    <a @if ($ordertype == 'total-orders') href="{{ route('agent_export.orders') }}" @elseif($ordertype == 'pending-orders') href="{{ route('agent_export.pending.orders') }}" @elseif($ordertype == 'approved-orders') href="{{ route('agent_export.approved.orders') }}" @elseif ($ordertype == 'incomplete-orders') href="{{ route('agent_export.incomplete.orders') }}" @elseif ($ordertype == 'unapproved-orders') href="{{ route('agent_export.unapproved.orders') }}"  @elseif ($ordertype == 'cancelled-orders') href="{{ route('agent_export.cancel.orders') }}" @elseif ($ordertype == 'completed-orders') href="{{ route('agent_export.completed.orders') }}" @endif
                                        class="btn btn-outline-primary float-right" data-placement="left">

                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-file-earmark-excel-fill" viewBox="0 0 16 16">
                                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64"/>
                                        </svg> --}}
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                   <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                     <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                 </svg>

                                    </a>
                                </div>
                            </div>

                        </div>

                        <div class="table-responsive">

                            <table id='order' class="table-montserrat table-sm table table-hover text-center">
                                @include('ledgers.partials.app')
                                <thead class="thead t-head-clr table-light">
                                    <tr class="t-head-clr">
                                        <th>No # </th>
                                        <th>Order No </th>
                                        <th>Order Detail No </th>
                                        {{-- <th>Agent Name</th> --}}
                                        <th>Customer Name</th>
                                        <th>Route</th>
                                        <th>Status</th>

                                        <th>Amount</th>
                                        {{-- <th>Action</th> --}}


                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $order->order->order_no }}</td>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ Str::title($order->order->partner_customer->name) }}</td>

                                            <td>{{$order->rate_list->name??null}}</td>
                                            <td>@php
                                                switch($order->status) {
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
                                            <span class="badge bg-light-{{$badgeClass}} text-{{$badgeClass}}  ">{{ Str::title($order->status) }}</span></td>
                                            <td>{{$order->rate??null}}</td>



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

<script>
    $(document).ready(function() {

});
</script>
