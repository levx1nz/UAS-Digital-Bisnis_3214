<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $fillable = ['nama_jabatan'];

    public function pengurus()
    {
        return $this->hasMany(Pengurus::class);
    }
}