<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'file_path',
        'department_id',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'audited_by',
        'audited_at',
        'audit_notes',
        'expected_review_date',
        'notes_by_employee',
        'checked',
        'expected_audit_date'
    ];

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_policy');
    }
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_policy');
    }

    // Policy belongs to a Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Policy was reviewed by an Employee
    public function reviewer()
    {
        return $this->belongsTo(Employee::class, 'reviewed_by');
    }

    // Policy was audited by an Employee
    public function auditor()
    {
        return $this->belongsTo(Employee::class, 'audited_by');
    }
}
