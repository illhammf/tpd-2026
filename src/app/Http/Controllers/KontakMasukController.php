<?php

namespace App\Http\Controllers;

use App\Models\KontakMasuk;
use Illuminate\Http\Request;

class KontakMasukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'nomor_whatsapp' => 'required|max:255',
            'subjek' => 'nullable|max:255',
            'pesan' => 'required',
        ]);

        KontakMasuk::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nomor_whatsapp' => $request->nomor_whatsapp,
            'subjek' => $request->subjek,
            'pesan' => $request->pesan,
            'status_pesan' => 'Baru',
        ]);

        return redirect()
            ->route('home')
            ->with('success', 'Terima kasih sudah bertanya. Pesan kamu sudah masuk dan akan kami balas lewat WhatsApp.');
    }
}