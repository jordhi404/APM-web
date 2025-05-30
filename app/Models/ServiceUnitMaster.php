<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceUnitMaster extends Model
{
    use HasFactory; 

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'ServiceUnitMaster';
    protected $primaryKey = 'ServiceUnitID';

    public function healthcareServiceUnits()
    {
        return $this->hasMany(HealthcareServiceUnit::class, 'ServiceUnitID', 'ServiceUnitID');
    }
}
