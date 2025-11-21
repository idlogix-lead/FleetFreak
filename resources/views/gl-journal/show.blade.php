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
                            <span class="card-title">{{ __('Show') }} Gl Journal</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('gl-journals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $glJournal->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Transaction Date:</strong>
                            {{ $glJournal->transaction_date }}
                        </div>
                        <div class="form-group">
                            <strong>Debit:</strong>
                            {{ $glJournal->debit }}
                        </div>
                        <div class="form-group">
                            <strong>Credit:</strong>
                            {{ $glJournal->credit }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $glJournal->description }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
