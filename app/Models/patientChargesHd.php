<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientChargesHd extends Model
{
    use HasFactory; 

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'PatientChargesHd';
    protected $primaryKey = 'TransactionID';

    public function patientBill()
    {
        return $this->belongsTo(PatientBill::class, 'PatientBillingID', 'PatientBillingID');
    }

    public function chargeDetails()
    {
        return $this->hasMany(PatientChargesDt::class, 'TransactionID', 'TransactionID');
    }

    public function healthcareServiceUnit()
    {
        return $this->belongsTo(HealthcareServiceUnit::class, 'HealthcareServiceUnitID', 'HealthcareServiceUnitID');
    }
}
