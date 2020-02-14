<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataHighwayLevel extends MetadataModelElement
{
    protected $table = 'datahighwaylevel';
    protected $primaryKey = 'hl_id';
    protected $changeColumn = 'ch_datahighwaylevel_id';
    public $timestamps = false;
    
    public function dataSets()
    {
        return $this->hasMany('App\DataSet', 'ds_datahighwaylevel_id');
    } 

    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_datahighwaylevel_id');
    }



    
    
}
