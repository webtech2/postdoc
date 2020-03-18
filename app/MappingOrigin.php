<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingOrigin extends Model
{
    protected $table = 'mappingorigin';
    public $timestamps = false;
    protected $primaryKey = null;
    public $incrementing = false;
    
    public function mapping()
    {
        return $this->belongsTo('App\Mapping', 'ms_mapping_id');
    }      
    
    public function originDataItem()
    {
        return $this->belongsTo('App\DataItem', 'ms_origin_dataitem_id');
    }      
    
    public function getID() 
    {
        return $this->attributes[$this->primaryKey];
    }    

    public function getPK() 
    {
        return $this->primaryKey;
    }
    
    
}
