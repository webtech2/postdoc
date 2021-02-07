<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Type;

class Change extends Model
{
    protected $table = 'change';
    protected $primaryKey = 'ch_id';
    public $timestamps = false;
    
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

    public function object() 
    {
        $objectType;
        $objectName;
        if ($object=$this->dataItem) {
            $objectType='DataItem';
            $objectName=$object->di_name;
        }
        else if ($object=$this->metadataProperty) {
            $objectType='MetadataProperty';         
            $objectName=$object->md_name;
        }
        else if ($object=$this->mapping) {
            $objectType='Mapping';    
            $objectName=$object->targetDataItem->di_name;
        }
        else if ($object=$this->dataHighwayLevel) {
            $objectType='DataHighwayLevel';   
            $objectName=$object->hl_name;
        } 
        else if ($object=$this->dataSet) {
            $objectType='DataSet';
            $objectName=$object->ds_name;
        } 
        else if ($object=$this->dataSource) {
            $objectType='DataSource';
            $objectName=$object->so_name;
        }  
        else if ($object=$this->relationship) {
            $objectType='Relationship';
            $objectName=$object->parentDataItem->di_name;
        }
        else {
            $object=null;
        }
        return compact('objectType', 'objectName', 'object');
    }
    
    public function getID() 
    {
        return $this->attributes[$this->primaryKey];
    }

    public function getPK() 
    {
        return $this->primaryKey;
    }

    public function getChangeType() 
    {
        $ch_typ = DB::select('select POSTDOC_METADATA.get_change_type('.$this->ch_id.') as ch_typ from dual')[0]->ch_typ; 
        if ($ch_typ)
            return Type::find($ch_typ);
        else 
            return null;
    }
    
    public function caManualConditionFulfillments()
    {
        return $this->hasMany('App\CaManualConditionFulfillment', 'camcf_change_id');
    }
    
    public function changeAdaptationAdditionalData()
    {
        return $this->hasMany('App\ChangeAdaptationAdditionalData', 'caad_change_id');
    }
    
    public function changeAdaptationProcess()
    {
        return $this->hasMany('App\ChangeAdaptationProcess', 'cap_change_id');
    }
    
}
