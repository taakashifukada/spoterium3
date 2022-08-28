<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Content extends Model
{
    use HasFactory;
    
    public function bookmark()   
    {
        return $this->belongsTo('App\Bookmark');  
    }
}
