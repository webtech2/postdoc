<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table = 'author';
    protected $primaryKey = 'au_id';
    
    public function changes()
    {
        return $this->hasMany('App\Change', 'ch_author_id');
    } 
    
    public function metadataProperties()
    {
        return $this->hasMany('App\MetadataProperty', 'md_author_id');
    } 

}
