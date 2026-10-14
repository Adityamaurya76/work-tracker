<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-logo">
                WT
            </div>
            <h1>Register for WorkTrack</h1>
            <p class="auth-subtitle">
                Create your account to get started.
            </p>

            @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif


            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name">

                         @error('name')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email">

                        @error('email')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimum 8 characters">

                        @error('password')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password">

                        @error('password_confirmation')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                </div>

                <button class="auth-button" type="submit">Register</button>
            </form>

            <p class="auth-footer">
                Already registered?
                <a href="{{ route('login') }}">Login</a>
            </p>

</body>

</html>