<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pengaturan->nama_website ?? 'Tukang Print Dadakan' }}</title>

    @if(!empty($pengaturan?->favicon))
    <link rel="icon"
        type="image/png"
        href="{{ asset('storage/' . $pengaturan->favicon) }}">
    @endif

    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>
<body>

@php
    $namaWebsite = $pengaturan->nama_website ?? 'Tukang Print Dadakan';

    $waAdmin = $pengaturan->nomor_whatsapp ?? '0895336900466';
    $waAdminClean = preg_replace('/[^0-9]/', '', $waAdmin);

    if (str_starts_with($waAdminClean, '0')) {
        $waAdminClean = '62' . substr($waAdminClean, 1);
    }

    $waText = rawurlencode('Halo Kak Ilham, saya mau tanya tentang layanan Tukang Print Dadakan.');

    $waWebUrl = "https://web.whatsapp.com/send?phone={$waAdminClean}&text={$waText}";
    $waMobileUrl = "whatsapp://send?phone={$waAdminClean}&text={$waText}";

    $bookingUrl = Route::has('booking.create') ? route('booking.create') : '#';
    $loginUrl = Route::has('login') ? route('login') : '#';
@endphp

<header class="navbar">
    <div class="container nav-wrapper">
        <a href="{{ route('home') }}" class="brand">
            @if(!empty($pengaturan?->logo))
                <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo">
            @else
                <span class="brand-icon">⚡</span>
            @endif
            <span>{{ $namaWebsite }}</span>
        </a>

        <button class="nav-toggle" id="navToggle">☰</button>

        <nav class="nav-menu" id="navMenu">
            <a href="#home">Home</a>
            <a href="#layanan">Layanan</a>
            <a href="#alur">Alur</a>
            <a href="#stok">Stok</a>
            <a href="#testimoni">Testimoni</a>
            <a href="#kontak">Kontak</a>

            @auth

                <a href="{{ route('booking.create') }}" class="btn-primary">
                    Pesan Sekarang
                </a>

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-secondary">
                        Logout
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}" class="btn-primary">
                    Login
                </a>

            @endauth
        </nav>
    </div>
</header>

<main>

    @if (session('success'))
        <div class="toast-success" id="toastSuccess">
            <strong>Pesan Terkirim ✅</strong>
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('toastSuccess').remove()">×</button>
        </div>
    @endif

    <section id="home" class="hero">
        <div class="container hero-wrapper">
            <div class="hero-content">
                <span class="badge">Khusus Mahasiswa • Pre-Order H-1</span>

                <h1>
                    {{ $pengaturan->judul_hero ?? 'Print Tugas Gak Perlu Ribet' }}
                </h1>

                <p>
                    {{ $pengaturan->deskripsi_hero ?? 'Kirim file dari rumah, aku print, lalu besoknya bisa COD di Kampus Esa Unggul Tangerang. Cocok buat tugas, modul, laporan, proposal, dan kebutuhan akademik lainnya.' }}
                </p>

                <div class="hero-actions">
                    <a href="{{ $bookingUrl }}" class="btn-primary">
                        Pesan Sekarang
                    </a>

                    <a href="#" onclick="openWhatsApp()" class="btn-secondary">
                        Chat WhatsApp
                    </a>
                </div>

                <div class="hero-info">
                    <div>
                        <strong>Rp500</strong>
                        <span>Hitam Putih / Fotokopi</span>
                    </div>
                    <div>
                        <strong>Rp900</strong>
                        <span>Print Warna</span>
                    </div>
                    <div>
                        <strong>H-1</strong>
                        <span>Kirim File Sebelum Hari Ambil</span>
                    </div>
                </div>
            </div>

            <div class="hero-card">
                @if(!empty($pengaturan?->gambar_hero))
                    <div class="hero-image-card">
                        <img src="{{ asset('storage/' . $pengaturan->gambar_hero) }}" alt="Gambar Hero">
                        <div class="floating-note">
                            Tenang, gak perlu buru-buru ke fotokopian!
                        </div>
                    </div>
                @else
                    <div class="paper-card">
                        <span class="paper-label">Sistem Order</span>
                        <h3>Kirim Hari Ini, Ambil Besok</h3>
                        <p>Upload file, pilih layanan, tentukan metode pembayaran, lalu konfirmasi via WhatsApp.</p>

                        <div class="order-flow">
                            <div>📄<span>Upload File</span></div>
                            <div>💳<span>Pilih Bayar</span></div>
                            <div>🛵<span>COD Kampus</span></div>
                        </div>
                    </div>

                    <div class="floating-note">
                        Tenang, gak perlu buru-buru ke fotokopian!
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="section stats-section">
        <div class="container stats-grid">
            <div class="stat-card">
                <span>📍</span>
                <h3>COD Kampus</h3>
                <p>Utama di Universitas Esa Unggul Tangerang.</p>
            </div>
            <div class="stat-card">
                <span>🗓️</span>
                <h3>Senin - Jumat</h3>
                <p>{{ $pengaturan->jam_operasional ?? 'Kecuali tanggal merah.' }}</p>
            </div>
            <div class="stat-card">
                <span>💸</span>
                <h3>Harga Mahasiswa</h3>
                <p>Murah, praktis, dan cocok buat anak kampus.</p>
            </div>
            <div class="stat-card">
                <span>📲</span>
                <h3>Konfirmasi WA</h3>
                <p>Komunikasi pesanan langsung lewat WhatsApp.</p>
            </div>
        </div>
    </section>

    <section id="layanan" class="section">
        <div class="container">
            <div class="section-heading">
                <span>Layanan</span>
                <h2>Pilih Kebutuhan Kamu</h2>
                <p>Semua layanan ini bisa diatur dari Filament Admin, jadi website tetap dinamis.</p>
            </div>

            <div class="service-grid">
                @forelse($layanans as $layanan)
                    <div class="service-card">
                        <div class="service-icon">
                            @if($layanan->gambar)
                                <img src="{{ asset('storage/' . $layanan->gambar) }}" alt="{{ $layanan->nama_layanan }}">
                            @else
                                @switch($layanan->slug)
                                    @case('print-dokumen') 🖨️ @break
                                    @case('fotokopi') 📄 @break
                                    @case('jilid-biasa') 📚 @break
                                    @case('laminating') 🪪 @break
                                    @case('rapihin-tugas') ✍️ @break
                                    @case('belajar-bareng') 💻 @break
                                    @default ⚡
                                @endswitch
                            @endif
                        </div>

                        <span class="service-category">
                            {{ $layanan->kategoriLayanan->nama_kategori ?? 'Layanan' }}
                        </span>

                        <h3>{{ $layanan->nama_layanan }}</h3>

                        <p>
                            {{ $layanan->deskripsi ?? 'Layanan praktis untuk kebutuhan mahasiswa.' }}
                        </p>

                        <div class="service-bottom">
                            <strong>Rp{{ number_format($layanan->harga_dasar, 0, ',', '.') }}</strong>
                            <span>/ {{ $layanan->satuan }}</span>
                        </div>

                        <a href="{{ $bookingUrl }}" class="service-link">
                            Pesan Layanan
                        </a>
                    </div>
                @empty
                    <div class="empty-card">
                        Belum ada layanan aktif. Tambahkan layanan melalui Filament Admin.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="alur" class="section section-dark">
        <div class="container">
            <div class="section-heading light">
                <span>Alur Pemesanan</span>
                <h2>Simple, Tapi Tetap Terstruktur</h2>
                <p>Sistem ini bukan print instan. Pesanan menggunakan konsep H-1 supaya hasil lebih siap dan rapi.</p>
            </div>

            <div class="timeline">
                <div class="timeline-item">
                    <span>01</span>
                    <h3>Login / Buat Akun</h3>
                    <p>Mahasiswa login dulu agar data pesanan bisa tersimpan dan mudah dicek.</p>
                </div>

                <div class="timeline-item">
                    <span>02</span>
                    <h3>Pilih Layanan</h3>
                    <p>Pilih print, fotokopi, jilid, laminating, rapihin tugas, atau belajar bareng.</p>
                </div>

                <div class="timeline-item">
                    <span>03</span>
                    <h3>Upload File</h3>
                    <p>Kirim dokumen, gambar, atau file tugas yang ingin diproses.</p>
                </div>

                <div class="timeline-item">
                    <span>04</span>
                    <h3>Pilih Pembayaran</h3>
                    <p>Cash saat COD atau online melalui QRIS, DANA, dan BRI.</p>
                </div>

                <div class="timeline-item">
                    <span>05</span>
                    <h3>Konfirmasi WhatsApp</h3>
                    <p>Pesanan masuk ke admin dan komunikasi lanjut lewat WhatsApp.</p>
                </div>

                <div class="timeline-item">
                    <span>06</span>
                    <h3>Ambil / Diantar</h3>
                    <p>Pesanan bisa COD kampus, diantar Ilham, atau menggunakan ojek online.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="stok" class="section">
        <div class="container">
            <div class="section-heading">
                <span>Stok Barang</span>
                <h2>Cek Kesiapan Print</h2>
                <p>User bisa melihat apakah kertas, tinta, dan perlengkapan lain sedang ready atau kosong.</p>
            </div>

            <div class="stock-grid">
                @forelse($stokBarangs as $stok)
                    <div class="stock-card {{ strtolower($stok->status_stok) }}">
                        <div>
                            <h3>{{ $stok->nama_barang }}</h3>
                            <p>{{ $stok->kategori ?? 'Perlengkapan' }}</p>
                        </div>

                        <div class="stock-info">
                            <strong>{{ $stok->jumlah }} {{ $stok->satuan }}</strong>
                            <span>{{ $stok->status_stok }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-card">
                        Data stok belum tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section price-section">
        <div class="container price-wrapper">
            <div>
                <span class="badge">Harga Mahasiswa</span>
                <h2>Makin Banyak Print, Makin Hemat</h2>
                <p>
                    Harga dibuat sederhana dan cocok untuk mahasiswa. Untuk layanan tambahan seperti jilid,
                    laminating, rapihin tugas, dan belajar bareng bisa disesuaikan dari admin.
                </p>
            </div>

            <div class="price-box">
                <div>
                    <span>Print / Fotokopi Hitam Putih</span>
                    <strong>Rp500 / lembar</strong>
                </div>
                <div>
                    <span>Print Warna</span>
                    <strong>Rp900 / lembar</strong>
                </div>
                <div>
                    <span>Pembulatan</span>
                    <strong>≤ 500 turun, > 500 naik</strong>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="section">
        <div class="container">
            <div class="section-heading">
                <span>Testimoni</span>
                <h2>Kata Mereka</h2>
                <p>Testimoni pelanggan bisa ditampilkan atau disembunyikan dari Filament Admin.</p>
            </div>

            <div class="testimonial-grid">
                @forelse($testimonis as $testimoni)
                    <div class="testimonial-card">
                        <div class="stars">
                            {{ str_repeat('⭐', (int) $testimoni->rating) }}
                        </div>
                        <p>“{{ $testimoni->komentar }}”</p>
                        <div class="testimonial-user">
                            @if($testimoni->foto)
                                <img src="{{ asset('storage/' . $testimoni->foto) }}" alt="{{ $testimoni->nama }}">
                            @else
                                <div class="avatar">{{ strtoupper(substr($testimoni->nama, 0, 1)) }}</div>
                            @endif
                            <div>
                                <strong>{{ $testimoni->nama }}</strong>
                                <span>{{ $testimoni->jurusan ?? 'Mahasiswa' }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-card">
                        Belum ada testimoni aktif.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="kontak" class="section contact-section">
        <div class="container contact-wrapper">
            <div class="contact-content">
                <span class="badge">Kontak</span>
                <h2>Punya Pertanyaan?</h2>
                <p>
                    Mau tanya dulu soal file, pembayaran, lokasi COD, atau layanan tambahan?
                    Kirim pesan aja, nanti bisa lanjut komunikasi lewat WhatsApp.
                </p>

                <div class="contact-list">
                    <p>📍 {{ $pengaturan->alamat ?? 'Universitas Esa Unggul Tangerang' }}</p>
                    <p>📲 {{ $pengaturan->nomor_whatsapp ?? '0895-3369-00466' }}</p>
                    <p>✉️ {{ $pengaturan->email ?? 'hello@tpd.test' }}</p>
                </div>
            </div>

            <form action="{{ Route::has('kontak.store') ? route('kontak.store') : '#' }}" method="POST" class="contact-form">
                @csrf

                <input type="text" name="nama" placeholder="Nama kamu" required>
                <input type="email" name="email" placeholder="Email kamu">
                <input type="text" name="nomor_whatsapp" placeholder="Nomor WhatsApp" required>
                <input type="text" name="subjek" placeholder="Subjek pesan">
                <textarea name="pesan" rows="5" placeholder="Tulis pesan kamu..." required></textarea>

                <button type="submit" class="btn-primary">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </section>

    <section class="cta-section">
        <div class="container cta-box">
            <h2>Siap Print Tanpa Ribet?</h2>
            <p>Login dulu, buat pesanan, upload file, lalu konfirmasi via WhatsApp.</p>
            <a href="{{ $bookingUrl }}" class="btn-primary">
                Mulai Pesan Sekarang
            </a>
        </div>
    </section>

</main>

<footer class="footer">
    <div class="container footer-wrapper">
        <p>{{ $pengaturan->teks_footer ?? 'Copyright © 2026 Tukang Print Dadakan' }}</p>
    </div>
</footer>

<a href="#" onclick="openWhatsApp()" class="floating-wa">
    💬
</a>

<script src="{{ asset('front/js/script.js') }}"></script>

<script>
function openWhatsApp() {

    let nomor = "{{ $waAdminClean }}";
    let pesan = encodeURIComponent(
        "Halo Kak Ilham, saya mau tanya tentang layanan Tukang Print Dadakan."
    );

    let isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (isMobile) {
        window.location.href =
            `whatsapp://send?phone=${nomor}&text=${pesan}`;
    } else {
        window.open(
            `https://web.whatsapp.com/send?phone=${nomor}&text=${pesan}`,
            '_blank'
        );
    }
}
</script>

<script>
    setTimeout(() => {
        const toast = document.getElementById('toastSuccess');
        if (toast) toast.remove();
    }, 5000);
</script>
</body>
</html>