<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaConditionMapping extends Model
{
    protected $table = 'ca_conditionmapping';
    protected $primaryKey = 'cacm_id';
    public $timestamps = false;
}
