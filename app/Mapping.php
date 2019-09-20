<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mapping extends Model
{
    protected $table = 'mapping';
    protected $primaryKey = 'mp_id';
    
    public function targetDataItem()
    {
        return $this->belongsTo('App\DataItem', 'mp_target_dataitem_id');
    }      
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_mapping_id');
    }      
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_mapping_id');
    }      
        
    public function mappingOrigin()
    {
        return $this->hasMany('App\MappingOrigin', 'ms_mapping_id');
    }      
    
}
