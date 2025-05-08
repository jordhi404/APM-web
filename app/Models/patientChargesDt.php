<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PatientChargesDt extends Model
{
    use HasFactory;

    // protected $connection = 'medinfras';
    protected $connection = 'medinfras_dev';
    protected $table = 'PatientChargesDt';
    protected $primaryKey = 'ID';

    public function chargesHd()
    {
        return $this->belongsTo(PatientChargesHd::class, 'TransactionID', 'TransactionID');
    }

    public function item()
    {
        return $this->belongsTo(ItemMaster::class, 'ItemID', 'ItemID');
    }
}
