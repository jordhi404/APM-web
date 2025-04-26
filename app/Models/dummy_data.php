<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class dummy_data extends Model
{
    use HasFactory;

    protected $connection = 'pgsql';
    protected $table = 'dummy_datas';
    protected $fillable = ['MR', 'name', 'email', 'phone', 'address'];

    public function tagihan()
    {
        return $this->hasMany(TagihanDummy::class, 'MR', 'MR');
    }
}
