@extends('layouts.app')


@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Order</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('orders.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <strong>Order No:</strong>
                                {{ $order->order_no }}
                            </div>
                            <div class="col-md-4 form-group">
                                <strong>Trip Type:</strong>
                                {{ Str::title(str_replace('_', ' ', $order->trip_type ?? '-')) }}
                            </div>
                            <div class="col-md-4 form-group">
                                <strong>Status:</strong>
                                <span class="badge bg-light-info text-info">{{ Str::title($order->overall_status) }}</span>
                            </div>
                            <div class="col-md-4 form-group">
                                <strong>Customer:</strong>
                                {{ Str::title($order->partner_customer->name ?? '-') }}
                            </div>
                            <div class="col-md-4 form-group">
                                <strong>Agent:</strong>
                                {{ Str::title($order->partner_business->company_name ?? '-') }}
                            </div>
                            <div class="col-md-4 form-group">
                                <strong>Vehicle:</strong>
                                {{ $order->vehicle_Class->name ?? '-' }} / {{ $order->vehicleModel->name ?? '-' }}
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table class="table table-montserrat table-sm table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Date</th>
                                        <th>Pickup Time</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Vehicle</th>
                                        <th>Driver</th>
                                        <th>Rate</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @forelse ($order->orderDetails as $line)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $line->date ?? '-' }}</td>
                                            <td>{{ $line->pickup_time ?? '-' }}</td>
                                            <td>{{ $line->from_loc ?? '-' }}</td>
                                            <td>{{ $line->to_loc ?? '-' }}</td>
                                            <td>{{ $line->vehicle->registration_no ?? '-' }}</td>
                                            <td>{{ Str::title($line->driver->name ?? '-') }}</td>
                                            <td>{{ $line->rate ?? '-' }}</td>
                                            <td>{{ Str::title($line->status ?? '-') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9">No order lines.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
