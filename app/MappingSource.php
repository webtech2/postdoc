<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MappingSource extends Model
{
    protected $table = 'mappingsource';
    protected $primaryKey = 'ms_mapping_id';

    public function mapping()
    {
        return $this->belongsTo('App\Mapping', 'ms_mapping_id');
    }      
    
    public function originDataItem()
    {
        return $this->belongsTo('App\DataItem', 'ms_origin_dataitem_id');
    }      
    
    
    
    
}
