<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pekerjaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_penduduk',
        'pekerjaan',
        'pendapatan',
        'jumlah',
        'status'
    ];

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_penduduk', 'id');
    }
}
