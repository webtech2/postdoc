<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relationship extends Model
{
    protected $table = 'relationship';
    protected $primaryKey = 'rl_id';
    
    public function parentDataItem()
    {
        return $this->belongsTo('App\DataItem', 'rl_parent_dataitem_id');
    }      
    
    public function type()
    {
        return $this->belongsTo('App\Type', 'rl_relationshiptype_id');
    }      

    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_relationship_id');
    }      
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_relationship_id');
    }       

    public function relationshipElements()
    {
        return $this->hasMany('App\RelationshipElement', 're_relationship_id');
    }     
}
