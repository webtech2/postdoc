<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetadataProperty extends MetadataModelElement
{
    protected $table = 'metadataproperty';
    protected $primaryKey = 'md_id';
    public $timestamps = false;
    protected $changeColumn = 'ch_metadataproperty_id';
    
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
    
    public function elementType() 
    {
        if ($this->dataItem) {
            return substr(get_class($this->dataItem),4);
        } else if ($this->mapping) {
            return substr(get_class($this->mapping),4);
        } else if ($this->dataHighwayLevel) {
            return substr(get_class($this->dataHighwayLevel),4);
        } else if ($this->dataSet) {
            return substr(get_class($this->dataSet),4);
        } else if ($this->dataSetInstance) {
            return substr(get_class($this->dataSetInstance),4);
        } else if ($this->dataSource) {
            return substr(get_class($this->dataSource),4);
        } else if ($this->relationship) {
            return substr(get_class($this->relationship),4);
        }
    }

    public function object() 
    {
        $objectType = $this->elementType();
        $objectName;
        if ($object=$this->dataItem) {
            $objectName=$object->di_name;
        }
        else if ($object=$this->mapping) {
            $objectName=$object->targetDataItem->di_name;
        }
        else if ($object=$this->dataHighwayLevel) {
            $objectName=$object->hl_name;
        } 
        else if ($object=$this->dataSet) {
            $objectName=$object->ds_name;
        } 
        else if ($object=$this->dataSource) {
            $objectName=$object->so_name;
        }  
        else if ($object=$this->relationship) {
            $objectName=$object->parentDataItem->di_name;
        }
        else {
            $object=null;
        }
        return compact('objectType', 'objectName', 'object');
    }    
    
}
