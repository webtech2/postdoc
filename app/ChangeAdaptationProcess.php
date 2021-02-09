<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationProcess extends Model
{
    protected $table = 'changeadaptationprocess';
    protected $primaryKey = 'cap_id';
    public $timestamps = false;

    public function change()
    {
        return $this->belongsTo('App\Change', 'cap_change_id');
    }      

    public function statusType()
    {
        return $this->belongsTo('App\Type', 'cap_statustype_id');
    }      

    public function changeAdaptationScenario()
    {
        return $this->belongsTo('App\ChangeAdaptationScenario', 'cap_scenario_id');
    }      
    
    public function manualConditionsFulfilled() 
    {
        foreach ($this->changeAdaptationScenario->caConditionMappings as $cond) {
            if ($cond->changeAdaptationCondition->type->tp_id=='CON0000002') {
                if ($cond->changeAdaptationCondition
                        ->caManualConditionFulfillments()
                        ->where('camcf_change_id',$this->cap_change_id)
                        ->first()->fulfillmentStatus->tp_id=='MCF0000001') {
                    return false;
                }
            }
        }
        return true;
    }
    
}
