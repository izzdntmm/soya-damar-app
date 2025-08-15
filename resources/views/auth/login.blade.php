<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" type="image/png" href="{{ asset('images/logo-title.png') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #f2f4f7;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 1rem;
      animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .container {
      background: #fff;
      padding: 2rem;
      border-radius: 1rem;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 1.2rem;
    }

    .logo img {
      width: 80px;
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

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 0.9rem;
      margin-bottom: 1rem;
      border: 1px solid #ddd;
      border-radius: 0.5rem;
      font-size: 14px;
    }

    button {
      width: 100%;
      padding: 0.9rem;
      background: linear-gradient(135deg, #4A90E2, #007BFF); /* Gradasi biru */
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
      margin: 0 0.5rem;
    }

    .extra-links a:hover {
      text-decoration: underline;
    }

    .alert {
      background: #e6f0ff;
      color: #004085;
      padding: 0.8rem;
      border-radius: 0.5rem;
      margin-bottom: 1rem;
      text-align: center;
      border: 1px solid #b8daff;
    }

    /* Responsiveness Extra */
    @media (max-width: 480px) {
      .container {
        padding: 1.5rem;
      }

      input[type="email"],
      input[type="password"],
      button {
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="logo">
      <img src="{{ asset('images/logo-soya.png') }}" alt="UMKM Logo">
    </div>
    <h2>Soya Damar Kedelai</h2>
    <p>Manajemen Penyetoran</p>

    {{-- Tampilkan alert error jika ada --}}
    @if(session('error'))
      <div class="alert">
        {{ session('error') }}
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      <input type="email" name="email" placeholder="Email" required autofocus>
      <input type="password" name="password" placeholder="Password" required>

      <button type="submit">Login</button>
    </form>

    <div class="extra-links">
      <a href="{{ route('register') }}">Register</a> |
      <a href="{{ route('password.request') }}">Lupa Password?</a>
    </div>
  </div>

</body>
</html>
