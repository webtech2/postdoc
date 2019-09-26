<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSet extends Model
{
    protected $table = 'dataset';
    protected $primaryKey = 'ds_id';
    
    public function dataSource()
    {
        return $this->belongsTo('App\DataSource', 'ds_datasource_id');
    }      
    
    public function velocityType()
    {
        return $this->belongsTo('App\Type', 'ds_velocity_id');
    }      
    
    public function roleType()
    {
        return $this->belongsTo('App\Type', 'ds_role_id');
    }      
    
    public function dataHighwayLevel()
    {
        return $this->belongsTo('App\DataHighwayLevel', 'ds_datahighwaylevel_id');
    }      
    
    public function formatType()
    {
        return $this->belongsTo('App\Type', 'ds_formattype_id');
    }      
    
    public function instances()
    {
        return $this->hasMany('App\DataSetInstance', 'si_dataset_id');
    }      
    
    public function dataItems()
    {
        return $this->hasMany('App\DataItem', 'di_dataset_id');
    }      
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_dataset_id');
    }      
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_dataset_id');
    }      
        
    public function lastChanged() // data items changed?
    {
        if ($this->changes) 
            return $this->changes()->get()[0]->ch_datetime;
        else 
            return $this->ds_created;
    }        
      
    
    
}
