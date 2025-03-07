<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory, SoftDeletes;

    // Define which attributes are mass assignable
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'source' table
    }
}
