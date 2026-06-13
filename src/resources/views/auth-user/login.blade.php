<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User - Tukang Print Dadakan</title>

    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>
<body class="auth-page">

<main class="auth-wrapper">
    <section class="auth-card">
        <div class="auth-left">
            <a href="{{ route('home') }}" class="brand auth-brand">
                <span class="brand-icon">⚡</span>
                <span>Tukang Print Dadakan</span>
            </a>

            <span class="badge">Login Mahasiswa</span>

            <h1>Masuk dulu, baru pesen print.</h1>

            <p>
                Login supaya data pesanan kamu tersimpan, gampang dicek, dan admin bisa konfirmasi lewat WhatsApp.
            </p>

            <div class="auth-feature">
                <div>📄 <span>Upload file tugas</span></div>
                <div>💳 <span>Cash / Online</span></div>
                <div>📲 <span>Konfirmasi WA</span></div>
            </div>
        </div>

        <div class="auth-right">
            <h2>Login Akun</h2>
            <p class="auth-subtitle">Gunakan email dan password yang sudah terdaftar.</p>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        required
                    >
                </div>

                <button type="submit" class="btn-primary auth-submit">
                    Login Sekarang
                </button>
            </form>

            <div class="auth-bottom">
                <p>Belum punya akun?</p>
                <a href="{{ route('register') }}">Buat akun dulu</a>
            </div>

            <a href="{{ route('home') }}" class="back-home">
                ← Kembali ke Beranda
            </a>
        </div>
    </section>
</main>

</body>
</html>