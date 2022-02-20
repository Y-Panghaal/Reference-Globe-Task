@extends('master')

@section('title', 'Edit User')

@section('style')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
        <span><a class="btn btn-secondary" href="{{ route('user.login.view') }}">Login</a></span>
    </div>
    <h1 class="h3 mb-3 fw-normal">Edit User</h1>
    <small class="text-disabled">Note: Only fill those fields you want to update. Leave others empty.</small>
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
        <div>
            {{ session('error') }}
        </div>
    </div>
    @endif
    @include('userForm', ['route' => route('user.edit', ['id' => Request::route('id')]), 'required' => false, 'method' => 'PUT', 'submitButton' => 'button'])
    <script>
        $(document).ready(function() {
            $(document.body).on('click', 'button#submitButton', function() {
                const formData = new FormData($('form')[0]);
                let entriesToDelete = [];
                for (const entry of formData.entries()) {
                    if ((entry[1] === '') || (entry[1] instanceof File && entry[1].size === 0)) {
                        entriesToDelete.push(entry[0]);
                    }
                }
                entriesToDelete.forEach(entry => $(`[name="${entry}"]`).removeAttr('name'));
                $('form')[0].submit();
            });
        });
    </script>
</body>
@endsection