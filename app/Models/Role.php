<?php

namespace App\Models;

use App\Traits\HasPermissions;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Sluggable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Return permissions
     *
     * @return array
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    /**
     * Return permissions
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_role');
    }

    /**
     * Check hasPermissions.
     *
     * @return $role->hasPermissionTo('edit-user', 'edit-issue');
     */
    public function hasPermissionTo(...$permissions)
    {
        return $this()->permissions()->whereIn('slug', $permissions)->count();
    }
}
