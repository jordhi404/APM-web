<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientBill extends Model
{
    use HasFactory;
    
    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'PatientBill';
    protected $primaryKey = 'PatientBillingID';

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'RegistrationID', 'RegistrationID');
    }

    public function chargesHd()
    {
        return $this->hasMany(patientChargesHd::class, 'PatientBillingID', 'PatientBillingID');
    }
}
