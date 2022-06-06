<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalDatang extends Model
{
    use HasFactory;
    protected $fillable = ['keterangan', 'harga', 'idModal', 'nama_pembuat', 'status'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function riwayat_modal_datang()
    {
        return $this->hasMany(RiwayatModalDatang::class, 'idModalDatang')->orderBy('id', 'DESC');
    }
}
