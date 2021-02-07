<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeAdaptationAdditionalData extends Model
{
    protected $table = 'changeadaptationadditionaldata';
    protected $primaryKey = 'caad_id';
    public $timestamps = false;

    public function change()
    {
        return $this->belongsTo('App\Change', 'caad_change_id');
    }    
    
    public function type()
    {
        return $this->belongsTo('App\Type', 'caad_data_type_id');
    }    
    
}
