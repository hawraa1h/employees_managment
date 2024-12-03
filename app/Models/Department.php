<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
    ];

    public function policies()
    {
        return $this->belongsToMany(Policy::class, 'department_policy');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
