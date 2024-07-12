<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisBantuanPenduduk extends Model
{
    use HasFactory;

    protected $table = 'jenis_bantuan_penduduk';
    protected $fillable = [
        'penduduk_id','jenis_bantuan_id'
    ];

    public function jenisBantuan()
    {
        return $this->hasOne(JenisBantuan::class, 'id','jenis_bantuan_id');
    }
}
