<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
    use HasFactory;

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'Registration';
    protected $primaryKey = 'RegistrationID';

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'MRN', 'MRN');
    }

    public function patientBills()
    {
        return $this->hasMany(PatientBill::class, 'RegistrationID', 'RegistrationID');
    }
}
