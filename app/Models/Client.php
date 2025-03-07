<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id'); // 'author' is the foreign key in the 'projects' table
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'projects' table
    }
    public function projects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }
}
