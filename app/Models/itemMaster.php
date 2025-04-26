<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class itemMaster extends Model
{
    use HasFactory;

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'ItemMaster';
    protected $primaryKey = 'ItemID';
}
