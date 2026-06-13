<?php

namespace App\Http\Controllers;

use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\PengaturanWebsite;
use App\Models\StokBarang;
use App\Models\Testimoni;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'pengaturan' => PengaturanWebsite::first(),
            'kategoriLayanans' => KategoriLayanan::where('status', true)
                ->with(['layanans' => fn ($query) => $query->where('status', true)])
                ->get(),
            'layanans' => Layanan::where('status', true)->with('kategoriLayanan')->get(),
            'stokBarangs' => StokBarang::latest()->get(),
            'testimonis' => Testimoni::where('status', true)->latest()->take(6)->get(),
        ]);
    }
}