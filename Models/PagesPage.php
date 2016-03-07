<?php

namespace NineCells\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagesPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'writer_id',
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