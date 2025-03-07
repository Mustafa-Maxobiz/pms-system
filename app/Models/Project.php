<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    // Define which attributes are mass assignable
    protected $guarded = [];


    public function source()
    {
        return $this->belongsTo(Source::class, 'source_id', 'id'); // 'source_id' is the foreign key in the 'projects' table
    }
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    } 
    public function externalStatus()
    {
        return $this->belongsTo(ExternalStatus::class, 'external_status', 'id');
    } 
    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id'); // 'author' is the foreign key in the 'projects' table
    }
}
