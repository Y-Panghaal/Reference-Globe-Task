@extends('master')

@section('title', 'List of users')

@section('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
@endsection

@section('body')
<body class="container text-center">
    <div class="row mt-5" style="text-align: right;">
        @if((int)\Auth::user()->role > 0)
        <span><a class="btn btn-primary add-user" href="{{ route('user.new.view') }}">Add User</a></span>
        @endif
        <span><a class="btn btn-secondary" href="{{ route('logout') }}">Logout</a></span>
    </div>
    <h1 class="mt-5">Users</h1>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Signature</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>
    </div>
    <script type="application/javascript">
        $(document).ready(async function() {
            console.log("Working");
            const ajax = (url, type = "GET", data = {}) => {
                return new Promise((resolve, reject) => {
                    let options = {
                        url: url,
                        type: type,
                        dataType: 'json',
                        success: response => resolve(response),
                        error: jqXHR => reject(jqXHR)
                    };
                    if (Object.keys(data).length > 0) {
                        options.data = data;
                    }
                    $.ajax(options);
                });
            };

            const fetchUsersList = async () => {
                try {
                    const response = await ajax('{{ route('users') }}', 'GET', {contentType: 'html'});

                    if (response.status !== 'ok') {
                        alert(response.message);
                        return;
                    }

                    $('tbody#tableBody').html(response.users);
                } catch (e) {
                    console.error(e);
                    alert("Something went wrong. Please refresh the page.");
                }
            };
            fetchUsersList();

        });
    </script>
</body>
@endsection