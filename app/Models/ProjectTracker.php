<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTracker extends Model {
    use HasFactory;

    protected $table = 'projectTrackers';

    protected $fillable = [
        'name',
        'stage',
        'status',
        'priority',
        'start_date',
    ];
}
