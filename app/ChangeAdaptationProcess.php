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
    
}
