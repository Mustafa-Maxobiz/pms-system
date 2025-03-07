<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;

class TaskType extends Model implements ContractsAuditable
{
    use HasFactory, SoftDeletes, Auditable;

    protected $auditInclude = ['title', 'evg_time'];

    // Define which attributes are mass assignable
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
}
