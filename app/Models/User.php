<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as ContractsAuditable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements ContractsAuditable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes, Auditable;

    protected $auditInclude = ['name', 'profile_picture'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
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
    /**
     * Relationship with Projects
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'author', 'id');
    }

    /**
     * Relationship with Clients
     */
    public function clients()
    {
        return $this->hasMany(Client::class, 'author', 'id');
    }
    public function nextOfKins()
    {
        return $this->hasMany(NextOfKin::class);
    }

    public function tasks()
    {
        return $this->hasMany(TaskAssignments::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(UserLog::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function rolename()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id');
    }

    public function getSingleRoleAttribute()
    {
        return Role::join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', $this->id)
            ->select('roles.name')
            ->first()?->name; // `first()` ka use pehla record lene ke liye, `?->name` to get only role name
    }

}
