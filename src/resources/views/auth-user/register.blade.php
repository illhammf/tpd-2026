<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Tukang Print Dadakan</title>

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

            <span class="badge">Akun Mahasiswa</span>

            <h1>Buat akun, pesen print jadi gampang.</h1>

            <p>
                Daftar sekali aja. Setelah itu kamu bisa pesan layanan, upload file, pilih pembayaran,
                dan lanjut konfirmasi lewat WhatsApp.
            </p>

            <div class="auth-feature">
                <div>👤 <span>Data pemesan otomatis</span></div>
                <div>📲 <span>Nomor WA untuk konfirmasi</span></div>
                <div>🧾 <span>Pesanan masuk ke admin</span></div>
            </div>
        </div>

        <div class="auth-right">
            <h2>Buat Akun</h2>
            <p class="auth-subtitle">Isi data sederhana di bawah ini untuk mulai memesan.</p>

            @if ($errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="auth-form">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Ilham Firmansyah"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <input
                        type="text"
                        name="nomor_whatsapp"
                        value="{{ old('nomor_whatsapp') }}"
                        placeholder="Contoh: 0895336900466"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Minimal 6 karakter"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Ulangi password"
                        required
                    >
                </div>

                <button type="submit" class="btn-primary auth-submit">
                    Daftar & Mulai Pesan
                </button>
            </form>

            <div class="auth-bottom">
                <p>Sudah punya akun?</p>
                <a href="{{ route('login') }}">Login di sini</a>
            </div>

            <a href="{{ route('home') }}" class="back-home">
                ← Kembali ke Beranda
            </a>
        </div>
    </section>
</main>

</body>
</html>