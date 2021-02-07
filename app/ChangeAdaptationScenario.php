<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationScenario extends Model
{
    protected $table = 'changeadaptationscenario';
    protected $primaryKey = 'cas_id';
    public $timestamps = false;

    public function changeAdaptationProcesses()
    {
        return $this->hasMany('App\ChangeAdaptationProcess', 'cap_scenario_id');
    }

    public function caConditionMappings()
    {
        return $this->hasMany('App\CaConditionMapping', 'cacm_scenario_id');
    }

    public function changeType()
    {
        return $this->belongsTo('App\Type', 'cas_changetype_id');
    }    

    public function changeAdaptationOperation()
    {
        return $this->belongsTo('App\ChangeAdaptationOperation', 'cas_operation_id');
    }    

    public function parentScenario()
    {
        return $this->belongsTo(self::class, 'cas_parentscenario_id', 'cas_id');
    }

    public function childScenarios()
    {
        return $this->hasMany(ChangeAdaptationScenario::class, 'cas_parentscenario_id', 'cas_id');
    }
    
}
