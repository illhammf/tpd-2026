<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>
<body class="booking-page">

@php
    $waWebUrl = 'https://web.whatsapp.com/send?phone=' . $wa . '&text=' . rawurlencode($pesanWa);
    $waMobileUrl = 'https://wa.me/' . $wa . '?text=' . rawurlencode($pesanWa);
@endphp

<section class="section">
    <div class="container">
        <div class="cta-box">
            <h2>Pesanan Berhasil Dibuat 🎉</h2>

            <p>
                Kode pesanan kamu:
                <strong>{{ $pesanan->kode_pesanan }}</strong>
            </p>

            <p>
                Pesanan sudah masuk ke admin. Klik tombol WhatsApp untuk konfirmasi.
                Kalau link WhatsApp error, copy pesan di bawah lalu kirim manual.
            </p>

            <div style="display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin:22px 0;">
                <a href="{{ $waWebUrl }}" target="_blank" class="btn-primary">
                    Buka WhatsApp Web
                </a>

                <a href="{{ $waMobileUrl }}" target="_blank" class="btn-secondary">
                    Buka WhatsApp HP
                </a>
            </div>

            <textarea
                readonly
                onclick="this.select()"
                style="width:100%; max-width:720px; min-height:180px; border-radius:18px; padding:18px; margin-top:18px;"
            >{{ $pesanWa }}</textarea>

            <br><br>

            <a href="{{ route('home') }}" class="btn-secondary">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>

</body>
</html>