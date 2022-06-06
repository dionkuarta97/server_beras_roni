<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penjualan extends Model
{
    use HasFactory;
    protected $fillable = ['idKategori', 'idModal', 'idBerasKelola', 'keterangan', 'bobot', 'harga_modal', 'harga_jual', 'tipe', 'idLangganan', 'jenis_pembayaran', 'nama_pembuat', 'nama_pembeli', 'status'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function modal_penjualan()
    {
        return $this->hasMany(ModalPenjualan::class, 'idPenjualan')->orderBy('id', 'DESC');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'idKategori', 'id');
    }
}
