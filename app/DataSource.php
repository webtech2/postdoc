<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DataSet;

class DataSource extends MetadataModelElement
{
    protected $table = 'datasource';
    protected $primaryKey = 'so_id';
    protected $changeColumn = 'ch_datasource_id';
    public $timestamps = false;
    
    public function dataSets()
    {
        return $this->hasMany(DataSet::class, 'ds_datasource_id')->orderBy('ds_deleted', 'desc')->orderBy('ds_name');
    }        
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_datasource_id');
    }      

    
}
