<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalKelola extends Model
{
    use HasFactory;
    protected $fillable = ['keterangan', 'harga', 'idBerasKelola', 'nama_pembuat', 'status'];
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
