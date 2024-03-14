<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerima extends Model
{
    use HasFactory;
    protected $fillable = ['nik','no_kk','nama','status_pkj','jk','jb','foto_diri'];
    public $timestamp = true;
}
