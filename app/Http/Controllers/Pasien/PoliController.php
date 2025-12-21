<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\DaftarPoli;
use Illuminate\Support\Facades\Auth;

class PoliController extends Controller
{
    /**
     * Menampilkan Halaman Daftar Poli
     */
    public function get()
    {
        // 1. Ambil Data User yang sedang login (untuk No RM dan ID Pasien)
        $user = Auth::user();

        // 2. Ambil Data Poli
        $polis = Poli::all();

        // 3. Ambil Data Jadwal beserta Relasi Dokter dan Polinya
        // (PENTING: Harus ada relasi 'dokter.poli' agar JavaScript filter bekerja)
        $jadwals = JadwalPeriksa::with(['dokter.poli'])->get(); 
        return view('pasien.daftar', compact('user', 'polis', 'jadwals')); 
    }

    public function submit(Request $request)
    {
        // Validasi Input
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksa,id',
            'keluhan' => 'required|string',
        ]);

        // Cek No Antrian Terakhir 
        $jumlahAntrian = DaftarPoli::where('id_jadwal', $request->id_jadwal)->count();
        
        // Nomor antrian baru
        $noAntrianBaru = $jumlahAntrian + 1;

        // Simpan ke Database
        DaftarPoli::create([
            'id_pasien' => Auth::id(),
            'id_jadwal' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'no_antrian' => $noAntrianBaru,
        ]);

        return redirect()->route('pasien.daftar')->with('message', 'Berhasil mendaftar! Nomor Antrian Anda: ' . $noAntrianBaru);
    }
}