@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- resources/views/auth/passwords/email.blade.php -->

                        <!-- Other HTML and email content -->

                        <p>
                            Here is your password reset token: {{ $token }}
                        </p>

                        <!-- Other HTML and email content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
