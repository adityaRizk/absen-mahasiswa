<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Absensi</title>
    <style>
        /* Styling Umum untuk Body/Background */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5; /* Latar belakang abu-abu muda */
    display: flex;
    justify-content: center; /* Pusatkan horizontal */
    align-items: center; /* Pusatkan vertikal */
    min-height: 100vh;
    margin: 0;
}

/* Container utama Form Login */
.login-container {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Efek bayangan */
    width: 100%;
    max-width: 380px;
    text-align: center;
}

/* Header/Judul Form */
.login-container h1 { /* Menggunakan h1 sesuai struktur baru */
    color: #34495e;
    margin-bottom: 25px;
    font-size: 2em;
    font-weight: 700;
}

/* Group Input Div */
.login-container > div {
    margin-bottom: 15px;
    text-align: left;
}

/* Label */
.login-container label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #555;
}

/* Styling Input Field */
.login-container input[type="text"],
.login-container input[type="password"] {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 1em;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.login-container input:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    outline: none;
}

/* Tombol Submit */
.login-container button[type="submit"] {
    width: 100%;
    background-color: #007bff;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.1em;
    font-weight: 600;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.login-container button[type="submit"]:hover {
    background-color: #0056b3;
}

/* Styling untuk Error Message (text-danger) */
.text-danger {
    color: #dc3545; /* Merah */
    font-size: 0.9em;
    display: block; /* Agar menempati baris baru */
    margin-top: 5px;
}
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="identity">NIM/NIP/Username</label>
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
    </div>
</body>
</html>
