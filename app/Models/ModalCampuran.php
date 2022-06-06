<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalCampuran extends Model
{
    use HasFactory;
    protected $fillable = ['harga', 'idBerasKelola', 'idModalKelola', 'idCampuran', 'berat', 'perbandingan', 'nama_pembuat'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
