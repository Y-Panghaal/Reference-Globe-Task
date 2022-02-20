@extends('master')

@section('title', 'Register')

@section('style')
<link rel="stylesheet" href="signin.css">
<style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>
@endsection

@section('body')
<body class="flex-column justify-content-center">
    <div class="row" style="text-align: right;">
        <span><a class="btn btn-secondary" href="route('dashboard')">Dashboard</a></span>
    </div>
    <h1 class="h3 mb-3 fw-normal">New User</h1>
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            {{ session('error') }}
        </div>
    </div>
    @endif
    @include('userForm', ['route' => route('user.new')])
</body>
@endsection