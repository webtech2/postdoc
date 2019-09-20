<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataItem extends Model
{
    protected $table = 'dataitem';
    protected $primaryKey = 'di_id';
    
    public function dataSet()
    {
        return $this->belongsTo('App\DataSet', 'di_dataset_id');
    }      

    public function itemType()
    {
        return $this->belongsTo('App\Type', 'di_itemtype_id');
    }      
    
    public function role()
    {
        return $this->belongsTo('App\Type', 'di_role_id');
    }      
    
    public function relationships()
    {
        return $this->hasMany('App\Relationship', 'rl_parent_dataitem_id');
    }      
    
    public function relationshipElements()
    {
        return $this->hasMany('App\RelationshipElement', 're_child_dataitem_id');
    }      
    
    public function mappingOrigins()
    {
        return $this->hasMany('App\MappingOrigin', 'ms_origin_dataitem_id');
    }      
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_dataitem_id');
    }      
    
    public function mappings()
    {
        return $this->hasMany('App\Mapping', 'mp_target_dataitem_id');
    }      
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_dataitem_id');
    }      
    
    
}
