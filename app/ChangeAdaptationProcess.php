<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationProcess extends Model
{
    protected $table = 'changeadaptationprocess';
    protected $primaryKey = 'cap_id';
    public $timestamps = false;
}
