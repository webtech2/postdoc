<?php

namespace App;

use App\Traits\HasCompositePrimaryKeyTrait;
use Illuminate\Database\Eloquent\Model;

class RelationshipElement extends Model
{
    use HasCompositePrimaryKeyTrait;
    
    protected $table = 'relationshipelement';
    protected $primaryKey = ['re_child_dataitem_id', 're_relationship_id'];
    public $incrementing = false;
    
    public function childDataItem()
    {
        return $this->belongsTo('App\DataItem', 're_child_dataitem_id');
    }     
    
    public function relationship()
    {
        return $this->belongsTo('App\Relationship', 're_relationship_id');
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
