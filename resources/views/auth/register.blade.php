<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo-title.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      min-height: 100%;
      background-color: #f0f4f8;
      overflow-x: hidden;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(120deg, #4A90E2, #6db3f2, #007BFF);
      background-size: 400% 400%;
      animation: moveBackground 15s ease infinite;
      z-index: -1;
      opacity: 0.25;
    }

    @keyframes moveBackground {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .wrapper {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem 1rem;
      min-height: 100vh;
    }

    .container {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      width: 100%;
      max-width: 420px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1rem;
    }

    .logo img {
      width: 70px;
    }

    h2 {
      text-align: center;
      margin-bottom: 0.5rem;
      color: #333;
    }

    p {
      text-align: center;
      color: #777;
      margin-bottom: 1.5rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.9rem;
      margin-bottom: 1rem;
      border: 1px solid #ddd;
      border-radius: 0.5rem;
      font-size: 14px;
    }

    .error {
      color: red;
      font-size: 12px;
      margin-top: -0.8rem;
      margin-bottom: 0.8rem;
    }

    button {
      width: 100%;
      padding: 0.9rem;
      background: linear-gradient(135deg, #4A90E2, #007BFF);
      border: none;
      border-radius: 0.5rem;
      color: white;
      font-weight: 600;
      font-size: 15px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: linear-gradient(135deg, #3A7FD5, #006FDE);
    }

    .extra-links {
      margin-top: 1rem;
      text-align: center;
      font-size: 13px;
    }

    .extra-links a {
      color: #007BFF;
      text-decoration: none;
    }

    .extra-links a:hover {
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .container {
        padding: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <div class="logo">
        <img src="{{ asset('images/logo-soya.png') }}" alt="UMKM Logo">
      </div>
      <h2>Register Akun</h2>
      <p>Manajemen Penyetoran</p>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
        @error('name')
          <div class="error">{{ $message }}</div>
        @enderror

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email')
          <div class="error">{{ $message }}</div>
        @enderror

        <input type="password" name="password" placeholder="Password" required>
        @error('password')
          <div class="error">{{ $message }}</div>
        @enderror

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
        @error('password_confirmation')
          <div class="error">{{ $message }}</div>
        @enderror

        <button type="submit">Register</button>
      </form>

      <div class="extra-links">
        <a href="{{ route('login') }}">Sudah punya akun? Login</a>
      </div>
    </div>
  </div>
</body>
</html>
