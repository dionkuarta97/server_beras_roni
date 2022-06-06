<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modal extends Model
{
    use HasFactory;
    protected $fillable = ['keterangan', 'berat', 'harga', 'idCategory', 'idUser', 'nama_pembuat',  'stock', 'status'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory', 'id');
    }

    public function riwayat_modal()
    {
        return $this->hasMany(RiwayatModal::class, 'idModal')->orderBy('id', 'DESC');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'idModal')->orderBy('id', 'DESC');
    }
}
