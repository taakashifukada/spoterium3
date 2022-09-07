<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Folder extends Model
{
    use HasFactory;
    
    public function bookmarks()   
    {
        return $this->hasMany('App\Bookmark');  
    }
}
