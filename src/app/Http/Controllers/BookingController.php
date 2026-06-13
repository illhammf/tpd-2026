<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\HariLibur;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\PengaturanBooking;
use App\Models\PengaturanWebsite;
use App\Models\Pengiriman;
use App\Models\Pesanan;
use App\Models\RiwayatStatusPesanan;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create()
    {
        return view('booking.create', [
            'layanans' => Layanan::where('status', true)->get(),
            'pengaturan' => PengaturanWebsite::first(),
            'aturan' => PengaturanBooking::first(),
        ]);
    }

    public function store(Request $request)
    {
        $aturan = PengaturanBooking::first() ?? new PengaturanBooking([
            'wajib_h_minus_satu' => true,
            'batas_jam_booking' => '22:00:00',
            'tutup_sabtu' => true,
            'tutup_minggu' => true,
            'tutup_tanggal_merah' => true,
            'maksimal_lembar_per_hari' => 500,
            'maksimal_lembar_per_order' => 150,
            'maksimal_jadwal_belajar_per_jam' => 1,
            'minimal_hari_rapihin_tugas' => 2,
            'biaya_jilid' => 5000,
            'biaya_laminating' => 3000,
            'biaya_prioritas' => 5000,
            'aktifkan_order_prioritas' => true,
            'wajib_upload_bukti_online' => true,
            'ongkir_kampus' => 0,
            'lokasi_luar_kampus_perlu_konfirmasi' => true,
            'ojek_online_perlu_konfirmasi' => true,
        ]);

        $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'nomor_whatsapp' => 'required',
            'tanggal_pengambilan' => 'required|date',
            'jam_pengambilan' => 'nullable',
            'lokasi_pengambilan' => 'required',
            'metode_pengiriman' => 'nullable',
            'metode_pembayaran' => 'required|in:Cash,Online',
            'channel_pembayaran' => 'nullable|in:Cash,QRIS,DANA,BRI',
            'jumlah_halaman' => 'nullable|numeric|min:1',
            'jumlah_copy' => 'nullable|numeric|min:1',
            'jumlah_sesi' => 'nullable|numeric|min:1',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'bukti_transfer' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $layanan = Layanan::findOrFail($request->layanan_id);
        $namaLayanan = strtolower($layanan->nama_layanan);

        $isBelajar = str_contains($namaLayanan, 'belajar');
        $isRapihin = str_contains($namaLayanan, 'rapihin');
        $isAkademik = $isBelajar || $isRapihin;
        $isPrintLike = ! $isAkademik;

        $tanggalAmbil = Carbon::parse($request->tanggal_pengambilan)->startOfDay();
        $hariIni = now()->startOfDay();

        if ($aturan->wajib_h_minus_satu && $tanggalAmbil->lessThanOrEqualTo($hariIni)) {
            return back()->withInput()->withErrors([
                'tanggal_pengambilan' => 'Pesanan wajib H-1. Kamu tidak bisa memilih tanggal hari ini atau tanggal yang sudah lewat.',
            ]);
        }

        $batasJam = Carbon::parse(now()->toDateString() . ' ' . $aturan->batas_jam_booking);

        if (
            $aturan->wajib_h_minus_satu &&
            now()->greaterThan($batasJam) &&
            $tanggalAmbil->isSameDay(now()->addDay())
        ) {
            return back()->withInput()->withErrors([
                'tanggal_pengambilan' => 'Booking untuk besok sudah ditutup karena melewati batas jam ' . Carbon::parse($aturan->batas_jam_booking)->format('H:i') . '. Silakan pilih lusa atau tanggal lain.',
            ]);
        }

        if ($aturan->tutup_sabtu && $tanggalAmbil->isSaturday()) {
            return back()->withInput()->withErrors([
                'tanggal_pengambilan' => 'Hari Sabtu tidak tersedia untuk booking.',
            ]);
        }

        if ($aturan->tutup_minggu && $tanggalAmbil->isSunday()) {
            return back()->withInput()->withErrors([
                'tanggal_pengambilan' => 'Hari Minggu tidak tersedia untuk booking.',
            ]);
        }

        if ($aturan->tutup_tanggal_merah) {
            $hariLibur = HariLibur::whereDate('tanggal', $tanggalAmbil)
                ->where('status', true)
                ->first();

            if ($hariLibur) {
                return back()->withInput()->withErrors([
                    'tanggal_pengambilan' => 'Tanggal tersebut libur: ' . $hariLibur->nama_libur . '. Silakan pilih tanggal lain.',
                ]);
            }
        }

        if ($isRapihin) {
            $minimalTanggal = now()->addDays((int) $aturan->minimal_hari_rapihin_tugas)->startOfDay();

            if ($tanggalAmbil->lessThan($minimalTanggal)) {
                return back()->withInput()->withErrors([
                    'tanggal_pengambilan' => 'Layanan Rapihin Tugas minimal H-' . $aturan->minimal_hari_rapihin_tugas . ' agar hasilnya rapi dan tidak terburu-buru.',
                ]);
            }
        }

        if ($isBelajar && $request->jam_pengambilan) {
            $jumlahJadwalBelajar = Pesanan::whereDate('tanggal_pengambilan', $tanggalAmbil)
                ->where('jam_pengambilan', $request->jam_pengambilan)
                ->whereHas('detailPesanans.layanan', function ($query) {
                    $query->where('nama_layanan', 'like', '%Belajar%');
                })
                ->whereNotIn('status_pesanan', ['Dibatalkan', 'Selesai'])
                ->count();

            if ($jumlahJadwalBelajar >= $aturan->maksimal_jadwal_belajar_per_jam) {
                return back()->withInput()->withErrors([
                    'jam_pengambilan' => 'Jadwal belajar bareng pada jam tersebut sudah penuh. Silakan pilih jam lain.',
                ]);
            }
        }

        $jumlahHalaman = (int) ($request->jumlah_halaman ?? 1);
        $jumlahCopy = (int) ($request->jumlah_copy ?? 1);
        $jumlahSesi = (int) ($request->jumlah_sesi ?? 1);

        $totalLembarOrder = $isPrintLike ? ($jumlahHalaman * $jumlahCopy) : 0;

        if ($isPrintLike && $totalLembarOrder > $aturan->maksimal_lembar_per_order) {
            return back()->withInput()->withErrors([
                'jumlah_halaman' => 'Maksimal per order adalah ' . $aturan->maksimal_lembar_per_order . ' lembar. Untuk order besar, hubungi admin lewat WhatsApp.',
            ]);
        }

        if ($isPrintLike) {
            $lembarTerpakaiHariItu = DetailPesanan::whereHas('pesanan', function ($query) use ($tanggalAmbil) {
                $query->whereDate('tanggal_pengambilan', $tanggalAmbil)
                    ->whereNotIn('status_pesanan', ['Dibatalkan']);
            })
                ->sum(\DB::raw('jumlah_halaman * jumlah_copy'));

            if (($lembarTerpakaiHariItu + $totalLembarOrder) > $aturan->maksimal_lembar_per_hari) {
                return back()->withInput()->withErrors([
                    'tanggal_pengambilan' => 'Kuota print tanggal ini sudah penuh. Sisa kuota: ' . max(0, $aturan->maksimal_lembar_per_hari - $lembarTerpakaiHariItu) . ' lembar.',
                ]);
            }
        }

        if ($isPrintLike) {
            $stokError = $this->cekStok($request);

            if ($stokError) {
                return back()->withInput()->withErrors([
                    'stok' => $stokError,
                ]);
            }
        }

        if (
            $request->metode_pembayaran === 'Online' &&
            $aturan->wajib_upload_bukti_online &&
            ! $request->hasFile('bukti_transfer')
        ) {
            return back()->withInput()->withErrors([
                'bukti_transfer' => 'Pembayaran online wajib upload bukti transfer.',
            ]);
        }

        $filePath = null;

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('pesanan', 'public');
        }

        $buktiTransferPath = null;

        if ($request->hasFile('bukti_transfer')) {
            $buktiTransferPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
        }

        if ($isAkademik) {
            $subtotal = $jumlahSesi * $layanan->harga_dasar;
        } else {
            $subtotal = $totalLembarOrder * $layanan->harga_dasar;
        }

        $biayaTambahan = 0;

        if ($request->has('pakai_jilid')) {
            $biayaTambahan += (int) $aturan->biaya_jilid;
        }

        if ($request->has('pakai_laminating')) {
            $biayaTambahan += (int) $aturan->biaya_laminating;
        }

        if ($aturan->aktifkan_order_prioritas && $request->has('order_prioritas')) {
            $biayaTambahan += (int) $aturan->biaya_prioritas;
        }

        $metodePengiriman = $request->metode_pengiriman ?? ($isAkademik ? 'Online' : 'COD Kampus');

        $biayaPengiriman = 0;
        $statusPengiriman = 'Belum Siap';

        if ($metodePengiriman === 'COD Kampus') {
            $biayaPengiriman = (int) $aturan->ongkir_kampus;
        }

        if (
            $request->lokasi_pengambilan === 'Lokasi Lain' &&
            $aturan->lokasi_luar_kampus_perlu_konfirmasi
        ) {
            $statusPengiriman = 'Menunggu Konfirmasi Ongkir';
        }

        if (
            $metodePengiriman === 'Ojek Online' &&
            $aturan->ojek_online_perlu_konfirmasi
        ) {
            $statusPengiriman = 'Menunggu Konfirmasi Ongkir';
        }

        $total = $subtotal + $biayaTambahan + $biayaPengiriman;

        $statusPesanan = match (true) {
            $request->metode_pembayaran === 'Online' => 'Menunggu Pembayaran',
            $statusPengiriman === 'Menunggu Konfirmasi Ongkir' => 'Menunggu Konfirmasi',
            default => 'Menunggu Konfirmasi',
        };

        $pesanan = Pesanan::create([
            'user_id' => Auth::id(),
            'kode_pesanan' => 'TPD-' . now()->format('YmdHis') . '-' . Auth::id(),
            'nama_pelanggan' => Auth::user()->name,
            'email' => Auth::user()->email,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'tanggal_pesan' => now()->toDateString(),
            'tanggal_pengambilan' => $request->tanggal_pengambilan,
            'jam_pengambilan' => $request->jam_pengambilan,
            'lokasi_pengambilan' => $isAkademik
                ? 'Kampus Esa Unggul Tangerang'
                : $request->lokasi_pengambilan,
            'detail_lokasi' => $isAkademik
                ? 'Metode Bantuan: ' . ($request->metode_bantuan ?? 'Online')
                : $request->detail_lokasi,
            'catatan' => $isAkademik
                ? trim(
                    'Topik/Kebutuhan: ' . ($request->topik_bantuan ?? '-') . "\n" .
                    'Metode Bantuan: ' . ($request->metode_bantuan ?? 'Online') . "\n" .
                    ($request->catatan ?? '')
                )
                : $request->catatan,
            'subtotal' => $subtotal,
            'biaya_tambahan' => $biayaTambahan,
            'biaya_pengiriman' => $biayaPengiriman,
            'total_harga' => $total,
            'status_pesanan' => $statusPesanan,
        ]);

        DetailPesanan::create([
            'pesanan_id' => $pesanan->id,
            'layanan_id' => $layanan->id,
            'nama_file' => $request->hasFile('file_path') ? $request->file('file_path')->getClientOriginalName() : null,
            'file_path' => $filePath,
            'jenis_print' => $isAkademik ? null : $request->jenis_print,
            'ukuran_kertas' => $isAkademik ? 'A4' : ($request->ukuran_kertas ?? 'A4'),
            'jumlah_halaman' => $isAkademik ? $jumlahSesi : $jumlahHalaman,
            'jumlah_copy' => $isAkademik ? 1 : $jumlahCopy,
            'harga_satuan' => $layanan->harga_dasar,
            'subtotal' => $subtotal,
            'pakai_jilid' => $isAkademik ? false : $request->has('pakai_jilid'),
            'pakai_laminating' => $isAkademik ? false : $request->has('pakai_laminating'),
            'catatan_detail' => $isAkademik
                ? trim(($request->topik_bantuan ? 'Topik/Kebutuhan: ' . $request->topik_bantuan . "\n" : '') . ($request->catatan_detail ?? ''))
                : $request->catatan_detail,
        ]);

        Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'channel_pembayaran' => $request->metode_pembayaran === 'Cash'
                ? 'Cash'
                : ($request->channel_pembayaran ?? 'QRIS'),
            'jumlah_bayar' => $total,
            'bukti_transfer' => $buktiTransferPath,
            'status_pembayaran' => $request->metode_pembayaran === 'Online'
                ? 'Menunggu Validasi'
                : 'Cash Saat COD',
            'tanggal_bayar' => $request->metode_pembayaran === 'Online' ? now() : null,
        ]);

        Pengiriman::create([
            'pesanan_id' => $pesanan->id,
            'metode_pengiriman' => $metodePengiriman,
            'alamat_pengiriman' => $request->detail_lokasi,
            'biaya_pengiriman' => $biayaPengiriman,
            'status_pengiriman' => $statusPengiriman,
            'catatan_pengiriman' => $statusPengiriman === 'Menunggu Konfirmasi Ongkir'
                ? 'Lokasi/ojek online perlu dikonfirmasi admin.'
                : null,
        ]);

        RiwayatStatusPesanan::create([
            'pesanan_id' => $pesanan->id,
            'status' => $statusPesanan,
            'catatan' => 'Pesanan baru dibuat oleh user melalui website.',
            'waktu_status' => now(),
        ]);

        $admin = PengaturanWebsite::first();
        $wa = preg_replace('/[^0-9]/', '', $admin->nomor_whatsapp ?? '0895336900466');

        if (str_starts_with($wa, '0')) {
            $wa = '62' . substr($wa, 1);
        }

        $pesanWa = $this->buatPesanWhatsApp($pesanan, $layanan, $request, $total, $isAkademik);

        return view('booking.success', [
            'pesanan' => $pesanan,
            'wa' => $wa,
            'pesanWa' => $pesanWa,
            'waUrl' => 'https://api.whatsapp.com/send?phone=' . $wa . '&text=' . urlencode($pesanWa),
        ]);
    }

    private function cekStok(Request $request): ?string
    {
        $ukuranKertas = $request->ukuran_kertas ?? 'A4';

        if (! $this->stokReady('Kertas ' . $ukuranKertas)) {
            return 'Stok Kertas ' . $ukuranKertas . ' sedang kosong/menipis. Silakan pilih ukuran lain atau hubungi admin.';
        }

        if ($request->jenis_print === 'Hitam Putih' && ! $this->stokReady('Tinta Hitam')) {
            return 'Tinta hitam sedang tidak ready.';
        }

        if ($request->jenis_print === 'Warna' && ! $this->stokReady('Tinta Warna')) {
            return 'Tinta warna sedang tidak ready.';
        }

        if ($request->has('pakai_jilid') && ! $this->stokReady('Cover Jilid')) {
            return 'Stok cover jilid sedang tidak ready.';
        }

        if ($request->has('pakai_laminating') && ! $this->stokReady('Plastik Laminating')) {
            return 'Stok plastik laminating sedang tidak ready.';
        }

        return null;
    }

    private function stokReady(string $namaBarang): bool
    {
        $stok = StokBarang::where('nama_barang', 'like', '%' . $namaBarang . '%')->first();

        if (! $stok) {
            return true;
        }

        return $stok->status_stok === 'Ready' && $stok->jumlah > 0;
    }

    private function buatPesanWhatsApp(Pesanan $pesanan, Layanan $layanan, Request $request, int $total, bool $isAkademik): string
    {
        if ($isAkademik) {
            return "Halo Kak Ilham, saya sudah booking layanan {$layanan->nama_layanan} di Tukang Print Dadakan.\n\n"
                . "Kode: {$pesanan->kode_pesanan}\n"
                . "Nama: {$pesanan->nama_pelanggan}\n"
                . "Topik/Kebutuhan: " . ($request->topik_bantuan ?? '-') . "\n"
                . "Tanggal Konsultasi: {$pesanan->tanggal_pengambilan}\n"
                . "Jam: " . ($pesanan->jam_pengambilan ?? '-') . "\n"
                . "Metode: {$pesanan->lokasi_pengambilan}\n"
                . "Total: Rp" . number_format($total, 0, ',', '.') . "\n"
                . "Metode Bayar: {$request->metode_pembayaran}\n\n"
                . "Mohon dikonfirmasi ya kak.";
        }

        return "Halo Kak Ilham, saya sudah membuat pesanan di Tukang Print Dadakan.\n\n"
            . "Kode: {$pesanan->kode_pesanan}\n"
            . "Nama: {$pesanan->nama_pelanggan}\n"
            . "Layanan: {$layanan->nama_layanan}\n"
            . "Jenis Print: " . ($request->jenis_print ?? '-') . "\n"
            . "Ukuran: " . ($request->ukuran_kertas ?? 'A4') . "\n"
            . "Halaman: " . ($request->jumlah_halaman ?? '-') . "\n"
            . "Copy: " . ($request->jumlah_copy ?? '-') . "\n"
            . "Jilid: " . ($request->has('pakai_jilid') ? 'Ya' : 'Tidak') . "\n"
            . "Laminating: " . ($request->has('pakai_laminating') ? 'Ya' : 'Tidak') . "\n"
            . "Total: Rp" . number_format($total, 0, ',', '.') . "\n"
            . "Tanggal Ambil: {$pesanan->tanggal_pengambilan}\n"
            . "Metode Bayar: {$request->metode_pembayaran}\n\n"
            . "Mohon dicek ya kak.";
    }
}
