<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'emp_number',
//        'id_number',
        'mobile',
        'email',
        'email',
        'address',
        'image',
        'password',
        'updated_password_at',
        'department_id',
        'role_id',
    ];
    protected $dates = ['updated_password_at']; // Ensure updated_password_at is treated as a date

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'employee_policy');
    }

    public function getImagePathAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        return asset('def.png');
    }
}
