<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaConditionMapping extends Model
{
    protected $table = 'ca_conditionmapping';
    protected $primaryKey = 'cacm_id';
    public $timestamps = false;

    public function changeAdaptationCondition()
    {
        return $this->belongsTo('App\ChangeAdaptationCondition', 'cacm_condition_id');
    }    
    
    public function changeAdaptationScenario()
    {
        return $this->belongsTo('App\ChangeAdaptationScenario', 'cacm_scenario_id');
    }    

    
}
