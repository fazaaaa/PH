<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penduduk extends Model
{
    use HasFactory;

    protected $fillable = [
        'No_KK',
        'NIK',
        'pas_foto', // Add this line
        'Nama_lengkap',
        'Hbg_kel',
        'JK',
        'tmpt_lahir',
        'tgl_lahir',
        'Agama',
        'Pendidikan_terakhir',
        // 'jenis_bantuan_id',
        'Penerima_bantuan'
    ];

    public function pekerjaan()
    {
        return $this->hasOne(Pekerjaan::class, 'id_penduduk', 'id');
    }

    public function kondisiRumah()
    {
        return $this->hasOne(KondisiRumah::class, 'id_penduduk', 'id');
    }

    public function klasifikasi()
    {
        return $this->hasOne(Klasifikasi::class, 'id_penduduk', 'id');
    }

    public function hasil()
    {
        return $this->hasOne(Hasil::class, 'id_penduduk', 'id');
    }

    // public function jenisBantuan()
    // {
    //     return $this->belongsToMany(JenisBantuan::class, 'penduduk_id');
    // }
    
    public function manyJenisBantuanPenduduk()
    {
        return $this->hasMany(JenisBantuanPenduduk::class, 'penduduk_id','id');
    }

    public function jenisBantuans()
    {
        return $this->belongsToMany(JenisBantuan::class, 'jenis_bantuan_penduduk','penduduk_id','jenis_bantuan_id');
    }
}
