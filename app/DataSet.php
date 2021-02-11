<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataSet extends MetadataModelElement
{
    protected $table = 'dataset';
    protected $primaryKey = 'ds_id';
    protected $changeColumn = 'ch_dataset_id';
    public $timestamps = false;
    
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
        return $this->hasMany('App\DataItem', 'di_dataset_id')->orderBy('di_id');
    }      

    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_dataset_id');
    }  
      
    public function topDataItems()
    {
        if ($this->formatType->tp_type=='XML')
            return $this->hasMany('App\DataItem', 'di_dataset_id')
                ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                     ->from('relationshipelement')
                     ->whereRaw('re_child_dataitem_id = di_id');
            })->orderBy('di_id');
        else return $this->dataItems();
    }      


}
