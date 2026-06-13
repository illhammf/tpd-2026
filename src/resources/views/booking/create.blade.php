<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Pesanan - {{ $pengaturan->nama_website ?? 'Tukang Print Dadakan' }}</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
</head>
<body>

@php
    $qris = !empty($pengaturan?->qris) ? asset('storage/' . $pengaturan->qris) : null;

    $biayaJilid = $aturan->biaya_jilid ?? 5000;
    $biayaLaminating = $aturan->biaya_laminating ?? 3000;
    $biayaPrioritas = $aturan->biaya_prioritas ?? 5000;
    $batasJam = $aturan->batas_jam_booking ?? '22:00';
    $maxOrder = $aturan->maksimal_lembar_per_order ?? 150;
    $maxHari = $aturan->maksimal_lembar_per_hari ?? 500;
@endphp

<header class="navbar">
    <div class="container nav-wrapper">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-icon">⚡</span>
            <span>{{ $pengaturan->nama_website ?? 'Tukang Print Dadakan' }}</span>
        </a>

        <nav class="nav-menu active-desktop">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('home') }}#layanan">Layanan</a>
            <a href="{{ route('home') }}#stok">Stok</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-secondary">Logout</button>
            </form>
        </nav>
    </div>
</header>

<main class="booking-page">
    <section class="section">
        <div class="container booking-wrapper">

            <aside class="booking-info">
                <span class="badge">Booking Mahasiswa • H-1</span>

                <h1>Pesan layanan tanpa ribet.</h1>

                <p>
                    Isi form dengan benar. Pesanan akan masuk ke admin, tersimpan di database,
                    lalu kamu diarahkan ke WhatsApp untuk konfirmasi.
                </p>

                <div class="booking-note">
                    <h3>Aturan Booking</h3>
                    <ul>
                        <li>Pesanan print wajib H-1.</li>
                        <li>Booking untuk besok ditutup jam {{ \Carbon\Carbon::parse($batasJam)->format('H:i') }}.</li>
                        <li>Maksimal {{ $maxOrder }} lembar per order.</li>
                        <li>Kuota harian maksimal {{ $maxHari }} lembar.</li>
                        <li>Sabtu, Minggu, dan tanggal merah bisa diblokir admin.</li>
                    </ul>
                </div>

                <div class="booking-note">
                    <h3>Mode Layanan</h3>
                    <ul>
                        <li><strong>Print/Fotokopi</strong> memakai halaman, copy, file, jilid, laminating.</li>
                        <li><strong>Belajar/Rapihin Tugas</strong> memakai topik, sesi, dan metode bantuan.</li>
                        <li><strong>Online Transfer</strong> wajib upload bukti transfer.</li>
                    </ul>
                </div>

                <div class="payment-preview">
                    <h3>Pembayaran Online</h3>

                    @if($qris)
                        <img src="{{ $qris }}" alt="QRIS">
                    @endif

                    <p><strong>DANA:</strong> {{ $pengaturan->nomor_dana ?? '-' }}</p>
                    <p><strong>BRI:</strong> {{ $pengaturan->nomor_bri ?? '-' }}</p>
                    <p><strong>A/N:</strong> {{ $pengaturan->atas_nama_bri ?? '-' }}</p>
                </div>
            </aside>

            <form action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data" class="booking-form">
                @csrf

                @if ($errors->any())
                    <div class="alert-error">
                        <strong>Oops, ada data yang belum sesuai:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-section">
                    <h2>1. Data Pemesan</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" value="{{ auth()->user()->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" readonly>
                        </div>

                        <div class="form-group full">
                            <label>Nomor WhatsApp</label>
                            <input
                                type="text"
                                name="nomor_whatsapp"
                                value="{{ old('nomor_whatsapp', auth()->user()->nomor_whatsapp) }}"
                                placeholder="Contoh: 0895336900466"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>2. Pilih Layanan</h2>

                    <div class="form-grid">
                        <div class="form-group full">
                            <label>Layanan</label>
                            <select name="layanan_id" id="layananSelect" required>
                                <option value="">-- Pilih layanan --</option>
                                @foreach($layanans as $layanan)
                                    <option
                                        value="{{ $layanan->id }}"
                                        data-harga="{{ $layanan->harga_dasar }}"
                                        data-nama="{{ strtolower($layanan->nama_layanan) }}"
                                        data-satuan="{{ $layanan->satuan }}"
                                        {{ old('layanan_id') == $layanan->id ? 'selected' : '' }}
                                    >
                                        {{ $layanan->nama_layanan }} - Rp{{ number_format($layanan->harga_dasar, 0, ',', '.') }}/{{ $layanan->satuan }}
                                    </option>
                                @endforeach
                            </select>
                            <small id="serviceHint">Pilih layanan dulu agar form menyesuaikan otomatis.</small>
                        </div>

                        <div id="printFields" class="form-grid full">
                            <div class="form-group">
                                <label>Jenis Print</label>
                                <select name="jenis_print" id="jenisPrint">
                                    <option value="">Tidak perlu</option>
                                    <option value="Hitam Putih">Hitam Putih</option>
                                    <option value="Warna">Warna</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Ukuran Kertas</label>
                                <select name="ukuran_kertas" id="ukuranKertas">
                                    <option value="A4">A4</option>
                                    <option value="F4">F4</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Halaman</label>
                                <input type="number" name="jumlah_halaman" id="jumlahHalaman" value="{{ old('jumlah_halaman', 1) }}" min="1">
                                <small>Maksimal {{ $maxOrder }} lembar per order.</small>
                            </div>

                            <div class="form-group">
                                <label>Jumlah Copy</label>
                                <input type="number" name="jumlah_copy" id="jumlahCopy" value="{{ old('jumlah_copy', 1) }}" min="1">
                            </div>

                            <div class="form-group full">
                                <label>Upload File Print</label>
                                <input type="file" name="file_path" id="filePrint">
                                <small>PDF, DOC, DOCX, JPG, PNG. Maksimal 10MB.</small>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="pakai_jilid" id="pakaiJilid" data-biaya="{{ $biayaJilid }}">
                                <label for="pakaiJilid">Tambah Jilid Biasa (+Rp{{ number_format($biayaJilid, 0, ',', '.') }})</label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" name="pakai_laminating" id="pakaiLaminating" data-biaya="{{ $biayaLaminating }}">
                                <label for="pakaiLaminating">Tambah Laminating (+Rp{{ number_format($biayaLaminating, 0, ',', '.') }})</label>
                            </div>
                        </div>

                        <div id="academicFields" class="form-grid full" style="display:none;">
                            <div class="form-group full">
                                <label>Topik / Kebutuhan</label>
                                <input
                                    type="text"
                                    name="topik_bantuan"
                                    id="topikBantuan"
                                    value="{{ old('topik_bantuan') }}"
                                    placeholder="Contoh: Belajar Laravel, debugging error, rapihin laporan, daftar isi otomatis"
                                >
                            </div>

                            <div class="form-group">
                                <label>Jumlah Sesi / Paket</label>
                                <input type="number" name="jumlah_sesi" id="jumlahSesi" value="{{ old('jumlah_sesi', 1) }}" min="1">
                            </div>

                            <div class="form-group">
                                <label>Metode Bantuan</label>
                                <select name="metode_bantuan" id="metodeBantuan">
                                    <option value="Online">Online</option>
                                    <option value="Offline Kampus">Offline Kampus</option>
                                    <option value="Chat WhatsApp">Chat WhatsApp</option>
                                </select>
                            </div>

                            <div class="form-group full">
                                <label>Upload File Pendukung</label>
                                <input type="file" name="file_path" id="fileAkademik" disabled>
                                <small>Boleh upload file tugas, laporan, screenshot error, atau bahan belajar.</small>
                            </div>
                        </div>

                        <div class="form-check full" id="priorityBox">
                            <input type="checkbox" name="order_prioritas" id="orderPrioritas" data-biaya="{{ $biayaPrioritas }}">
                            <label for="orderPrioritas">
                                Jadikan Prioritas (+Rp{{ number_format($biayaPrioritas, 0, ',', '.') }})
                            </label>
                        </div>

                        <div class="form-group full">
                            <label>Catatan Detail</label>
                            <textarea name="catatan_detail" rows="3" placeholder="Contoh: cover warna, isi hitam putih, jangan bolak-balik, atau detail bantuan tugas.">{{ old('catatan_detail') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2 id="scheduleTitle">3. Jadwal & Lokasi</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label id="tanggalLabel">Tanggal Pengambilan</label>
                            <input type="date" name="tanggal_pengambilan" id="tanggalPengambilan" value="{{ old('tanggal_pengambilan') }}" required>
                        </div>

                        <div class="form-group">
                            <label id="jamLabel">Jam Pengambilan</label>
                            <input type="time" name="jam_pengambilan" id="jamPengambilan" value="{{ old('jam_pengambilan') }}">
                        </div>

                        <div id="pickupFields" class="form-grid full">
                            <div class="form-group">
                                <label>Lokasi Pengambilan</label>
                                <select name="lokasi_pengambilan" id="lokasiPengambilan" required>
                                    <option value="Kampus Esa Unggul Tangerang">Kampus Esa Unggul Tangerang</option>
                                    <option value="Lokasi Lain">Lokasi Lain</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Metode Pengiriman</label>
                                <select name="metode_pengiriman" id="metodePengiriman">
                                    <option value="COD Kampus">COD Kampus</option>
                                    <option value="Diantar Ilham">Diantar Ilham</option>
                                    <option value="Ojek Online">Ojek Online</option>
                                    <option value="Ambil Sendiri">Ambil Sendiri</option>
                                </select>
                            </div>

                            <div class="form-group full">
                                <label>Detail Lokasi</label>
                                <textarea name="detail_lokasi" id="detailLokasi" rows="3" placeholder="Contoh: Lobby kampus, depan parkiran, atau alamat lengkap.">{{ old('detail_lokasi') }}</textarea>
                            </div>
                        </div>

                        <div id="academicScheduleInfo" class="academic-schedule-info full" style="display:none;">
                            <h3>Mode Konsultasi Aktif</h3>
                            <p>
                                Untuk Belajar Bareng / Rapihin Tugas, tanggal dan jam dipakai sebagai jadwal konsultasi.
                                Detail teknis tetap dikonfirmasi ulang lewat WhatsApp.
                            </p>
                        </div>

                        <div class="form-group full">
                            <label>Catatan Pesanan</label>
                            <textarea name="catatan" rows="3" placeholder="Catatan umum untuk admin.">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>4. Pembayaran</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" id="metodePembayaran" required>
                                <option value="Cash">Cash Saat COD</option>
                                <option value="Online">Online / Transfer</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Channel Pembayaran</label>
                            <select name="channel_pembayaran" id="channelPembayaran">
                                <option value="Cash">Cash</option>
                                <option value="QRIS">QRIS</option>
                                <option value="DANA">DANA</option>
                                <option value="BRI">BRI</option>
                            </select>
                        </div>

                        <div class="payment-box full" id="paymentBox">
                            <h3>Info Pembayaran Online</h3>

                            @if($qris)
                                <img src="{{ $qris }}" alt="QRIS">
                            @endif

                            <p><strong>DANA:</strong> {{ $pengaturan->nomor_dana ?? '-' }}</p>
                            <p><strong>BRI:</strong> {{ $pengaturan->nomor_bri ?? '-' }}</p>
                            <p><strong>A/N:</strong> {{ $pengaturan->atas_nama_bri ?? '-' }}</p>

                            <div class="form-group">
                                <label>Upload Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" id="buktiTransfer">
                                <small>Wajib jika memilih pembayaran online. Format JPG, PNG, atau PDF.</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="total-box">
                    <div>
                        <span>Estimasi Total</span>
                        <strong id="totalHarga">Rp0</strong>
                    </div>

                    <div class="price-breakdown" id="priceBreakdown">
                        Pilih layanan untuk melihat rincian harga.
                    </div>
                </div>

                <button type="submit" class="btn-primary booking-submit">
                    Simpan Pesanan & Konfirmasi WhatsApp
                </button>
            </form>
        </div>
    </section>
</main>

<script src="{{ asset('front/js/script.js') }}"></script>

<script>
    const biayaJilid = {{ $biayaJilid }};
    const biayaLaminating = {{ $biayaLaminating }};
    const biayaPrioritas = {{ $biayaPrioritas }};

    const layananSelect = document.getElementById('layananSelect');
    const printFields = document.getElementById('printFields');
    const academicFields = document.getElementById('academicFields');

    const jumlahHalaman = document.getElementById('jumlahHalaman');
    const jumlahCopy = document.getElementById('jumlahCopy');
    const jumlahSesi = document.getElementById('jumlahSesi');

    const pakaiJilid = document.getElementById('pakaiJilid');
    const pakaiLaminating = document.getElementById('pakaiLaminating');
    const orderPrioritas = document.getElementById('orderPrioritas');

    const filePrint = document.getElementById('filePrint');
    const fileAkademik = document.getElementById('fileAkademik');

    const totalHarga = document.getElementById('totalHarga');
    const priceBreakdown = document.getElementById('priceBreakdown');
    const serviceHint = document.getElementById('serviceHint');

    const pickupFields = document.getElementById('pickupFields');
    const academicScheduleInfo = document.getElementById('academicScheduleInfo');
    const scheduleTitle = document.getElementById('scheduleTitle');
    const tanggalLabel = document.getElementById('tanggalLabel');
    const jamLabel = document.getElementById('jamLabel');
    const lokasiPengambilan = document.getElementById('lokasiPengambilan');

    const metodePembayaran = document.getElementById('metodePembayaran');
    const channelPembayaran = document.getElementById('channelPembayaran');
    const paymentBox = document.getElementById('paymentBox');
    const buktiTransfer = document.getElementById('buktiTransfer');

    function formatRupiah(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(value);
    }

    function selectedOption() {
        return layananSelect.options[layananSelect.selectedIndex];
    }

    function selectedNama() {
        return selectedOption()?.dataset?.nama || '';
    }

    function selectedHarga() {
        return parseInt(selectedOption()?.dataset?.harga || 0);
    }

    function isAcademicService() {
        const nama = selectedNama();
        return nama.includes('belajar') || nama.includes('rapihin');
    }

    function toggleFieldsByService() {
        const academic = isAcademicService();

        printFields.style.display = academic ? 'none' : 'grid';
        academicFields.style.display = academic ? 'grid' : 'none';

        pickupFields.style.display = academic ? 'none' : 'grid';
        academicScheduleInfo.style.display = academic ? 'block' : 'none';

        scheduleTitle.textContent = academic ? '3. Jadwal Konsultasi' : '3. Jadwal & Lokasi';
        tanggalLabel.textContent = academic ? 'Tanggal Konsultasi' : 'Tanggal Pengambilan';
        jamLabel.textContent = academic ? 'Jam Konsultasi' : 'Jam Pengambilan';

        filePrint.disabled = academic;
        fileAkademik.disabled = !academic;

        lokasiPengambilan.required = !academic;

        serviceHint.textContent = academic
            ? 'Mode akademik aktif: form berubah menjadi topik, sesi, dan metode bantuan.'
            : 'Mode print aktif: isi halaman, copy, file, dan opsi tambahan.';

        hitungTotal();
    }

    function togglePayment() {
        const online = metodePembayaran.value === 'Online';

        paymentBox.style.display = online ? 'block' : 'none';
        buktiTransfer.required = online;

        if (online && channelPembayaran.value === 'Cash') {
            channelPembayaran.value = 'QRIS';
        }

        if (!online) {
            channelPembayaran.value = 'Cash';
        }
    }

    function hitungTotal() {
        const harga = selectedHarga();
        const academic = isAcademicService();

        let subtotal = 0;
        let tambahan = 0;
        let detail = [];

        if (academic) {
            const sesi = parseInt(jumlahSesi.value || 1);
            subtotal = harga * sesi;

            detail.push(`Layanan akademik: ${sesi} sesi x ${formatRupiah(harga)}`);
        } else {
            const halaman = parseInt(jumlahHalaman.value || 0);
            const copy = parseInt(jumlahCopy.value || 0);
            const totalLembar = halaman * copy;

            subtotal = totalLembar * harga;

            detail.push(`Print: ${halaman} halaman x ${copy} copy = ${totalLembar} lembar`);
            detail.push(`Harga dasar: ${formatRupiah(harga)} / satuan`);

            if (pakaiJilid.checked) {
                tambahan += biayaJilid;
                detail.push(`Jilid: ${formatRupiah(biayaJilid)}`);
            }

            if (pakaiLaminating.checked) {
                tambahan += biayaLaminating;
                detail.push(`Laminating: ${formatRupiah(biayaLaminating)}`);
            }
        }

        if (orderPrioritas.checked) {
            tambahan += biayaPrioritas;
            detail.push(`Prioritas: ${formatRupiah(biayaPrioritas)}`);
        }

        const total = subtotal + tambahan;

        totalHarga.textContent = formatRupiah(total);
        priceBreakdown.innerHTML = detail.length
            ? detail.map(item => `<p>${item}</p>`).join('')
            : 'Pilih layanan untuk melihat rincian harga.';
    }

    [
        layananSelect,
        jumlahHalaman,
        jumlahCopy,
        jumlahSesi,
        pakaiJilid,
        pakaiLaminating,
        orderPrioritas,
        metodePembayaran,
        channelPembayaran
    ].forEach((el) => {
        if (!el) return;

        el.addEventListener('input', () => {
            toggleFieldsByService();
            togglePayment();
            hitungTotal();
        });

        el.addEventListener('change', () => {
            toggleFieldsByService();
            togglePayment();
            hitungTotal();
        });
    });

    toggleFieldsByService();
    togglePayment();
    hitungTotal();
</script>

</body>
</html>