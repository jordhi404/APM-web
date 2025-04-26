<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TagihanDummy extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $table = 'tagihan_dummies';
    protected $fillable = ['MR', 'Layanan', 'Qty'];

    public function pasien()
    {
        return $this->belongsTo(dummy_data::class, 'MR', 'MR');
    }
    
    public function harga () {
        return $this->belongsTo(list_harga::class, 'Layanan', 'Layanan');
    }
}
