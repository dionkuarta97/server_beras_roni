<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Langganan extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'alamat', 'keterangna', 'nama_pembuat', 'status'];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pejualan()
    {
        return $this->hasMany(Penjualan::class, 'idLangganan')->orderBy('id', 'DESC');
    }
}
