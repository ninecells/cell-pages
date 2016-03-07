<?php

namespace NineCells\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagesHistory extends Model
{
    protected $fillable = [
        'pages_page_id', 'title', 'slug', 'content', 'writer_id', 'created_at', 'updated_at'
    ];

    public function getMdContentAttribute()
    {
        $content = $this->attributes['content'];
        $parsedown = new MyParsedown();
        return $parsedown->text($content);
    }

    public function writer()
    {
        return $this->hasOne('App\User', 'id', 'writer_id');
    }
}
