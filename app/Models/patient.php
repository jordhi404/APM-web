<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class patient extends Model
{
    use HasFactory;

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'Patient';
    protected $primaryKey = 'MRN';

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'MRN', 'MRN');
    }
}
