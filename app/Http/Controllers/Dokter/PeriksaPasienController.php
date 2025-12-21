<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use App\Models\DetailPeriksa;
use App\Models\Obat;
use App\Models\Periksa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriksaPasienController extends Controller
{
    public function index()
    {
        $dokterId = Auth::id();
        $daftarPasien = DaftarPoli::with(['pasien', 'jadwalPeriksa', 'periksas'])
            ->whereHas('jadwalPeriksa', function ($query) use ($dokterId) {
                $query->where('id_dokter', $dokterId);
            })
            ->orderBy('no_antrian')
            ->get();

        return view('dokter.periksa-pasien.index', compact('daftarPasien'));
    }

    public function create($id)
    {
        $obats = Obat::where('stok', '>', 0)->get(); 
        
        return view('dokter.periksa-pasien.create', compact('obats', 'id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_daftar_poli' => 'required',
            'obat_json' => 'nullable',
            'catatan' => 'nullable|string',
            'biaya_periksa' => 'required|integer',
        ]);

        $obatIds = $request->obat_json ? json_decode($request->obat_json, true) : [];

        //  1. VALIDASI STOK 
        
        foreach ($obatIds as $idObat) {
            $obat = Obat::find($idObat);
            if (!$obat || $obat->stok < 1) {
                return redirect()->back()
                    ->with('message', 'Gagal! Stok obat ' . ($obat->nama_obat ?? 'tidak diketahui') . ' habis.')
                    ->with('type', 'danger');
            }
        }

        //  2. SIMPAN DATA PERIKSA
        $periksa = Periksa::create([
            'id_daftar_poli' => $request->id_daftar_poli,
            'tgl_periksa' => now(),
            'catatan' => $request->catatan,
            'biaya_periksa' => $request->biaya_periksa + 150000,
        ]);

        //3. SIMPAN DETAIL & KURANGI STOK 
        foreach ($obatIds as $idObat) {
            // Simpan ke tabel detail_periksa
            DetailPeriksa::create([
                'id_periksa' => $periksa->id,
                'id_obat' => $idObat,
            ]);

            // Kurangi stok obat sebanyak 1
            $obat = Obat::find($idObat);
            if ($obat) {
                $obat->decrement('stok'); 
            }
        }

        return redirect()->route('periksa.pasien.index')
            ->with('message', 'Pemeriksaan berhasil disimpan dan stok obat berkurang!')
            ->with('type', 'success');
    }
}