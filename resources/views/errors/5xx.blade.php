@extends('errors.shell')

@section('code', $exception->getStatusCode())
@section('title', 'Something went wrong')
@section('message', 'An unexpected error stopped this page from loading. We’ve logged it. Please try again in a moment.')
