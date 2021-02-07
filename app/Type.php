<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';
    protected $primaryKey = 'tp_id';
    public $incrementing = false;
    public $timestamps = false;
    
    public function parentType()
    {
        return $this->belongsTo(self::class, 'tp_parenttype_id', 'tp_id');
    }

    public function subTypes()
    {
        return $this->hasMany(Type::class, 'tp_parenttype_id', 'tp_id');
    }
    
    public function changesWithStatusType()
    {
        return $this->hasMany('App\Change', 'ch_statustype_id', 'tp_id');
    }    
    
    public function changesWithChangeType()
    {
        return $this->hasMany('App\Change', 'ch_changetype_id', 'tp_id');
    } 
    
    public function dataSetsWithVelocityType()
    {
        return $this->hasMany('App\DataSet', 'ds_velocity_id');
    }    

    public function dataSetsWithRole()
    {
        return $this->hasMany('App\DataSet', 'ds_role_id');
    }    

    public function dataSetsWithFormatType()
    {
        return $this->hasMany('App\DataSet', 'ds_formattype_id');
    }    
    
    public function dataItemsWithRole()
    {
        return $this->hasMany('App\DataItem', 'di_role_id');
    }     
    
    public function dataItemsWithType()
    {
        return $this->hasMany('App\DataItem', 'di_itemtype_id');
    }    
    
    public function relationshipsWithType()
    {
        return $this->hasMany('App\Relationship', 'rl_relationshiptype_id');
    }    

    public function caManualConditionsFulfillmentWithStatus()
    {
        return $this->hasMany('App\CaManualConditionFulfillment', 'camcf_fulfillmentstatus_id');
    }    
    
    public function additionalDataWithType()
    {
        return $this->hasMany('App\ChangeAdaptationAdditionalData', 'caad_data_type_id');
    }    

    public function changeAdaptationProcessesWithType()
    {
        return $this->hasMany('App\ChangeAdaptationProcess', 'cap_statustype_id');
    }    
    
    public function changeAdaptationConditionsWithType()
    {
        return $this->hasMany('App\ChangeAdaptationCondition', 'cac_conditiontype_id');
    }    

    public function changeAdaptationScenariosWithChangeType()
    {
        return $this->hasMany('App\ChangeAdaptationScenario', 'cas_changetype_id');
    }    

    public function changeAdaptationOperationsWithType()
    {
        return $this->hasMany('App\ChangeAdaptationOperation', 'cao_operationtype_id');
    }    

    public function getPK() 
    {
        return $this->primaryKey;
    }
}
