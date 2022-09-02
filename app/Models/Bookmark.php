<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Bookmark extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'url',
        'title',
        'category_id',
        'user_id',
        'tags',
        'img_path',
        'comment',
        'contents_flag',
    ];
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    
    public function contents()
    {
        return $this->hasmany('App\Models\Content');
    }
    
    public function tags(){
        return $this->belongsToMany('App\Models\Tag');
    }
    
    function getPaginateByLimit(int $limit_count = 5)
    {
        return $this::with(['category', 'tags', 'contents'])->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
}
