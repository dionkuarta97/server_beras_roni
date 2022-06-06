<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Campuran extends Model
{
    use HasFactory;
    protected $fillable = ['idBerasKelola', 'idBerasCampur', 'idModal', 'idKategori', 'kategori', 'harga', 'berat', 'perbandingan', 'status'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    public function modal_campuran()
    {
        return $this->hasMany(ModalCampuran::class, 'idCampuran')->orderBy('id', 'DESC');
    }
}
