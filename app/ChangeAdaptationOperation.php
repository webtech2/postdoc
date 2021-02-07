<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationOperation extends Model
{
    protected $table = 'changeadaptationoperation';
    protected $primaryKey = 'cao_id';
    public $timestamps = false;

    public function changeAdaptationScenarios()
    {
        return $this->hasMany('App\ChangeAdaptationScenario', 'cas_operation_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Type', 'cao_operationtype_id');
    }    
    
}
