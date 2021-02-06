<?php

namespace App;

use Author;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_tab';
    protected $primaryKey = 'us_id';
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'us_name', 'us_email', 'us_password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'us_password'
    ];
    
    public function getAuthPassword()
    {
        return $this->us_password;
    }
    
    public function author()
    {
        return $this->hasOne('App\Author', 'au_user_id');
    }    
    
    public function getID() 
    {
        return $this->attributes[$this->primaryKey];
    }

    public function getPK() 
    {
        return $this->primaryKey;
    }
    
    public function getAuthor()
    {
        $author = $this->author;
        if (!$author) {
            $author = new Author();
            $author->au_id = DB::select('select AUTHOR_AU_ID_SEQ.nextval as au_id from dual')[0]->au_id; 
            $author->au_username = $this->us_name;
            $author->user()->associate($this);
            $author->save();
        }
        return $author;
    }
}
