<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Change extends Model
{
    protected $table = 'change';
    protected $primaryKey = 'ch_id';
    
    public function changeType()
    {
        return $this->belongsTo('App\Type', 'ch_changetype_id');
    }    

    public function statusType()
    {
        return $this->belongsTo('App\Type', 'ch_statustype_id');
    }    
    
    public function metadataProperty()
    {
        return $this->belongsTo('App\MetadataProperty', 'ch_metadataproperty_id');
    }  
    
    public function dataItem()
    {
        return $this->belongsTo('App\DataItem', 'ch_dataitem_id');
    }      

    public function mapping()
    {
        return $this->belongsTo('App\Mapping', 'ch_mapping_id');
    }      

    public function dataHighwayLevel()
    {
        return $this->belongsTo('App\DataHighwayLevel', 'ch_datahighwaylevel_id');
    }      

    public function dataSet()
    {
        return $this->belongsTo('App\DataSet', 'ch_dataset_id');
    }      

    public function author()
    {
        return $this->belongsTo('App\Author', 'ch_author_id');
    }      

    public function dataSource()
    {
        return $this->belongsTo('App\DataSource', 'ch_datasource_id');
    }      

    public function relationship()
    {
        return $this->belongsTo('App\Relationship', 'ch_relationship_id');
    }      

    

    
    
}
