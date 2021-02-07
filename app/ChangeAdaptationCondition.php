<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationCondition extends Model
{
    protected $table = 'changeadaptationcondition';
    protected $primaryKey = 'cac_id';
    public $timestamps = false;

    public function caManualConditionFulfillments()
    {
        return $this->hasMany('App\CaManualConditionFulfillment', 'camcf_condition_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Type', 'cac_conditiontype_id');
    }    

    public function caConditionMappings()
    {
        return $this->hasMany('App\CaConditionMapping', 'cacm_condition_id');
    }
    
}
