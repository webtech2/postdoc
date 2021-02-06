<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mapping extends MetadataModelElement
{
    protected $table = 'mapping';
    protected $primaryKey = 'mp_id';
    protected $changeColumn = 'ch_mapping_id';
    public $timestamps = false;
    
    public function targetDataItem()
    {
        return $this->belongsTo('App\DataItem', 'mp_target_dataitem_id');
    }          
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_mapping_id');
    }      
        
    public function mappingOrigin()
    {
        return $this->hasMany('App\MappingOrigin', 'ms_mapping_id');
    }      
 
    public function buildOperation()
    {
        $origins = $this->mappingOrigin;
        $result = $this->mp_operation;
        foreach ($origins as $origin) {
            $result = str_replace('?'.$origin->ms_order.'?', 
                    (($origin->originDataItem->dataSet->dataSource) ? ($origin->originDataItem->dataSet->dataSource->so_name) : ($origin->originDataItem->dataSet->dataHighwayLevel->hl_name)) . "." . $origin->originDataItem->dataSet->ds_name.".".$origin->originDataItem->di_name, 
                    $result);
        }
        return $result;
    }      

}
