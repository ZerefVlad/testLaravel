<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
</head>
<body>
@if (isset($message))
    <h1>{{$message}}</h1>
@endif

@if (isset($existingLink))
    <h1>Unique link was created on this phone</h1>
    <p>Your unique link: <a href="{{ url($existingLink) }}">{{ url($existingLink) }}</a></p>
    <button onclick="window.location.href='{{ route('register') }}'">Back</button>
@elseif (isset($generatedLink))
    <h1>Unique link create</h1>
    <p>Your unique link: <a href="{{ url($generatedLink) }}">{{ url($generatedLink) }}</a></p>
    <button onclick="window.location.href='{{ route('register') }}'">Back</button>
@else
    <h1>Register</h1>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="phone">PhoneNumber:</label>
        <input type="text" name="phone" id="phone" required>
        <br>
        <button type="submit">Register</button>
    </form>
@endif
</body>
</html>
