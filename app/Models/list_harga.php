<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class list_harga extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $table = 'list_hargas';
    protected $fillable = ['Layanan', 'Biaya'];

    public function tagihan()
    {
        return $this->hasMany(TagihanDummy::class, 'Layanan', 'Layanan');
    }
}
