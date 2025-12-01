<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Absensi</title>
</head>
<body>
    <h1>Login</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="identity">NIM/NIP/Email</label>
            <input id="identity" type="text" name="identity" value="{{ old('identity') }}" required autofocus>
            @error('identity')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit">Login</button>
    </form>
</body>
</html>
