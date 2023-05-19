<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    
    const NEWS_API_SOURCE_ID = 1;
    const THE_GUARDIAN_API_SOURCE_ID = 2;
    const NEW_YORK_TIMES_API_SOURCE_ID = 3;

    public $table = 'news';
    protected $fillable = [
        'title',
        'body',
        'category',
        'author',
        'thumb',
        'web_url',
        'published_at',
        'source_id',
    ];
}
