<!DOCTYPE html>
<html>
<head>
    <title>Users List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('resources/css/app.css') }}">
</head>
<body>
    <div class="container">
        <div class="links grid">
            <a href="https://test.smakshopp.com/users">Users</a>
            <a href="https://test.smakshopp.com/register">Registration</a>
        </div>
        <div class="users grid">
            @foreach($users as $user)
            <div class="user">
                @if (Str::startsWith($user->photo, 'https'))
                    <img src="{{ asset($user->photo) }}" alt="User Photo">
                @else
                    <img src="{{ asset('public/storage/' . $user->photo) }}" alt="User Photo">
                @endif
                <p>{{ $user->name }}</p>
                <a href="https://test.smakshopp.com/users/{{ $user->id }}" class="btn btn-primary">Show more</a>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    {{ $users->render("pagination::bootstrap-4") }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
