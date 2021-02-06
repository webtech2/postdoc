<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationOperation extends Model
{
    protected $table = 'changeadaptationoperation';
    protected $primaryKey = 'cao_id';
    public $timestamps = false;
}
