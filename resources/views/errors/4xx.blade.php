@extends('errors.shell')

@section('code', $exception->getStatusCode())
@section('title', 'That request couldn’t be completed')
@section('message', 'Something about the request wasn’t right. Go back and try again.')
