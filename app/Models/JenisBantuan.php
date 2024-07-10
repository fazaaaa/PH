<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBantuan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_bantuan'
    ];

    public function penduduk()
    {
        return $this->belongsToMany(Penduduk::class, 'jenis_bantuan_penduduk','jenis_bantuan_id','id_penduduk');
    }
}
