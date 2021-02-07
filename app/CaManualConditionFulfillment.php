<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaManualConditionFulfillment extends Model
{
    protected $table = 'ca_manualconditionfulfillment';
    protected $primaryKey = 'camcf_id';
    public $timestamps = false;

    public function change()
    {
        return $this->belongsTo('App\Change', 'camcf_change_id');
    }    
    
    public function changeAdaptationCondition()
    {
        return $this->belongsTo('App\ChangeAdaptationCondition', 'camcf_condition_id');
    }    

    public function fulfillmentStatus()
    {
        return $this->belongsTo('App\Type', 'camcf_fulfillmentstatus_id');
    }    
    
}
