@extends('master')

@section('title', 'Login')

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
</style>
@endsection

@section('body')
<body class="text-center">
    <main class="form-signin">
        <form method="POST" action="{{ route('user.login') }}">
            @csrf
            {{-- <img class="mb-4" src="/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57"> --}}
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
            @if(session('success'))
            <div class="alert alert-success d-flex align-items-center" role="alert">
                {{ session('success') }}
            </div>
            @endif
            @if($errors->all())
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                {{ $errors->all()[0] }}
            </div>
            @endif
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
                <label for="email">Email address</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <label for="password">Password</label>
            </div>
            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
            <p class="mt-5 mb-3 text-muted">
                New User?
                <a href="{{ route('user.register.view') }}">Register</a>
            </p>
        </form>
    </main>
</body>
@endsection