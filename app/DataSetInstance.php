<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSetInstance extends Model
{
    protected $table = 'datasetinstance';
    protected $primaryKey = 'si_id';

    public function dataSet()
    {
        return $this->belongsTo('App\DataSet', 'si_dataset_id');
    }      

    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_datasetinstance_id');
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
