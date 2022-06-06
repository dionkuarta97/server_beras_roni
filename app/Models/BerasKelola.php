<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerasKelola extends Model
{
    use HasFactory;
    protected $fillable = ['idModal', 'keterangan', 'harga', 'berat', 'stock', 'tipe', 'nama_pembuat', 'status'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function modal_kelola()
    {
        return $this->hasMany(ModalKelola::class, 'idBerasKelola')->orderBy('id', 'DESC');
    }

    public function campuran()
    {
        return $this->hasMany(Campuran::class, 'idBerasKelola')->orderBy('id', 'DESC');
    }

    public function beras_campuran()
    {
        return $this->hasMany(Campuran::class, 'idBerasCampur')->orderBy('id', 'DESC');
    }

    public function modal_campuran()
    {
        return $this->hasMany(ModalCampuran::class, 'idBerasKelola')->orderBy('id', 'DESC');
    }
    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'idBerasKelola')->orderBy('id', 'DESC');
    }

    public function modal()
    {
        return $this->belongsTo(Modal::class, 'idModal', 'id');
    }
}
