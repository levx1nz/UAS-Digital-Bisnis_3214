<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    // Tambahkan deskripsi dan gaji disini
    protected $fillable = ['nama_pengurus', 'jabatan_id', 'deskripsi', 'gaji'];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class);
    }
}