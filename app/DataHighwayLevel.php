<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataHighwayLevel extends Model
{
    protected $table = 'datahighwaylevel';
    protected $primaryKey = 'hl_id';
    
    public function dataSets()
    {
        return $this->hasMany('App\DataSet', 'ds_datahighwaylevel_id');
    } 

    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_datahighwaylevel_id');
    }

    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_datahighwaylevel_id');
    }
    
}
