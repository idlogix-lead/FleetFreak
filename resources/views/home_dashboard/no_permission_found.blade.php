{{-- /unauthorized: where the RolePermissions middleware sends denied page requests. Same look as the 403 error page. --}}
@extends('errors.shell')

@section('code', '403')
@section('title', 'You don’t have access to this page')
@section('message', 'Your role doesn’t include permission for this page. If you think it should, ask your administrator.')
