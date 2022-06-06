<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RiwayatModal extends Model
{
    use HasFactory;
    protected $fillable = ['idModal', 'keterangan', 'berat', 'harga', 'idCategory', 'idUser', 'nama_pembuat',  'stock', 'status', 'tipe_ubah'];
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
