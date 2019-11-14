<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetadataModelElement extends Model
{
    protected $changeColumn;
    
    public function getPK() 
    {
        return $this->primaryKey;
    }
    
    public function getID() 
    {
        return $this->attributes[$this->primaryKey];
    }

    public function changes()
    {
        return $this->hasMany('App\Change', $this->changeColumn)->latest('ch_datetime');
    }  

    public function lastChanged() 
    {
        return ($this->changes()->count() ? $this->changes()->first()->ch_datetime : $this->so_created);
    }    
         
}
