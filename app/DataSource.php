<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DataSet;

class DataSource extends Model
{
    protected $table = 'datasource';
    protected $primaryKey = 'so_id';
    
    public function dataSets()
    {
        return $this->hasMany(DataSet::class, 'ds_datasource_id')->orderBy('ds_deleted', 'desc')->orderBy('ds_name');
    }     
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_datasource_id')->orderBy('ch_datetime', 'desc');;
    }      
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_datasource_id');
    }      
    
    public function lastChanged() 
    {
        return ($this->changes ? $this->changes()->get()[0]->ch_datetime : $this->so_created);
    }
    
}
