<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPeriksa extends Model
{
    protected $table = 'detail_periksa';
    
    // WAJIB: Matikan timestamps agar tidak error saat simpan obat
    public $timestamps = false;
    

    protected $fillable = [
        'id_periksa',
        'id_obat'
    ];

    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'id_periksa');
    }

    // PERBAIKAN: Fungsi ini sebelumnya kosong, harus diisi relasi ke Obat
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat');
    }
}