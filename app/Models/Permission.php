<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'perm_name',
        'perm_label',
    ];

    // Define the many-to-many relationship with Role
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
