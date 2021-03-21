<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model {

    protected $table = 'cache';
    protected $fillable = ['market', 'category', 'title', 'min', 'avar', 'max', 'stat', 'date', 'comment'];
    const UPDATED_AT = null;
}