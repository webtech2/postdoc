<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaManualConditionFulfillment extends Model
{
    protected $table = 'ca_manualconditionfulfillment';
    protected $primaryKey = 'camcf_id';
    public $timestamps = false;
}
