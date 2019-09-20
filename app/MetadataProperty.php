<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetadataProperty extends Model
{
    protected $table = 'metadataproperty';
    protected $primaryKey = 'md_id';

    public function dataItem()
    {
        return $this->belongsTo('App\DataItem', 'md_dataitem_id');
    }      

    public function mapping()
    {
        return $this->belongsTo('App\Mapping', 'md_mapping_id');
    }      

    public function dataHighwayLevel()
    {
        return $this->belongsTo('App\DataHighwayLevel', 'md_datahighwaylevel_id');
    }      

    public function dataSet()
    {
        return $this->belongsTo('App\DataSet', 'md_dataset_id');
    }      

    public function dataSetInstance()
    {
        return $this->belongsTo('App\DataSetInstance', 'md_datasetinstance_id');
    }      

    public function author()
    {
        return $this->belongsTo('App\Author', 'md_author_id');
    }      

    public function dataSource()
    {
        return $this->belongsTo('App\DataSource', 'md_datasource_id');
    }      

    public function relationship()
    {
        return $this->belongsTo('App\Relationship', 'md_relationship_id');
    }      
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_metadataproperty_id');
    }
    
    
    
}
