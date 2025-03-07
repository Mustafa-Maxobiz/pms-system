<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'user_logs';


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class);
    }

    /**
     * Relationship with Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Relationship with Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
