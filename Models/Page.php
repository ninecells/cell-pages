<?php

namespace NineCells\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $table = 'pages_pages';

    protected $fillable = [
        'title', 'slug', 'content', 'writer_id',
    ];

    public function getMdContentAttribute()
    {
        $content = $this->attributes['content'];
        $parsedown = new PageParsedown();
        return $parsedown->text($content);
    }

    public function writer()
    {
        return $this->hasOne('App\User', 'id', 'writer_id');
    }

    public function exists()
    {
        return !!$this->slug;
    }

    public function isTitle($key)
    {
        return ($this->exists() && $this->slug != $key);
    }
}
