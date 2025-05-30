<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HealthcareServiceUnit extends Model
{
    use HasFactory; 

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'HealthcareServiceUnit';
    protected $primaryKey = 'HealthcareServiceUnitID';

    public function serviceUnit()
    {
        return $this->belongsTo(ServiceUnitMaster::class, 'ServiceUnitID', 'ServiceUnitID');
    }

    public function chargesHd()
    {
        return $this->hasMany(PatientChargesHd::class, 'HealthcareServiceUnitID', 'HealthcareServiceUnitID');
    }
}
