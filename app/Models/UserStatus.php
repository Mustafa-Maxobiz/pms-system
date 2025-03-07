<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatus extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function author()
    {
        return $this->belongsTo(User::class, 'author', 'id');
    }
    public function showDashboard()
    {
        $userStatuses = UserStatus::all();
        return view('dashboard', compact('userStatuses'));
    }
    public function setIcon($icon)
    {
        $this->icon = $icon;
        $this->save();
    }
}
