@extends('emails.layouts.app')
@section('email')
    {{-- <title>Registration Notification</title> --}}


        <div class="header">
            Registration Successful!
        </div>
        <div class="content">
            <h1>Hello!</h1>
            <p>You have successfully registered. Here are your account details:</p>
            <div class="details">
                {{-- <p><strong>Name:</strong> John Doe</p> --}}
                <p><strong>Email:</strong> {{$user->email}}</p>
                <p><strong>Password:</strong> {{$password}}</p>
            </div>
            <p>Thank you for joining us!</p>
        </div>

@endsection

@push('style')
    <style>
        .content {
            padding: 20px;
        }
        .content h1 {
            color: #640D5F;
            font-size: 20px;
            margin-bottom: 10px;
        }
        .content p {
            margin: 0 0 15px;
        }
        .details {
            border: 1px solid #640D5F;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .details p {
            margin: 5px 0;
            font-weight: bold;
            color: #640D5F;
        }
    </style>
@endpush
