<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>User Details</h1>
        <p><strong>ID:</strong> {{ $user->id }}</p>
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Phone:</strong> {{ $user->phone }}</p>
        <p><strong>Position:</strong> {{ $user->position->name }}</p>
        <p><strong>Photo:</strong></p>
        @if (Str::startsWith($user->photo, 'https'))
            <img src="{{ asset($user->photo) }}" alt="User Photo">
        @else
            <img src="{{ asset('public/storage/' . $user->photo) }}" alt="User Photo">
        @endif
    </div>
</body>
</html>