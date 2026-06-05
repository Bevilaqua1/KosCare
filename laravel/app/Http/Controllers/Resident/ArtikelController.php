<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\ArtikelEdukasi;

class ArtikelController extends Controller
{
    public function index()
    {
        $artikels = ArtikelEdukasi::latest('tanggal_terbit')->get();
        return view('Resident.artikel.index', compact('artikels'));
    }

    public function show(ArtikelEdukasi $artikel)
    {
        return view('Resident.artikel.show', compact('artikel'));
    }
}