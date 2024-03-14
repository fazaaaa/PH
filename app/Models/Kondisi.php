<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kondisi extends Model
{
    use HasFactory;
    protected $fillable = ['nik','id_penerima','tmpt_berteduh','jenis_lantai','jenis_dinding','fasilitas_mck','sumber_listrik','foto_rumah'];
    public $timestamp = true;
}
