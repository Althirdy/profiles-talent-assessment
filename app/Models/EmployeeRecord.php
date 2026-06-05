<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRecord extends Model
{
    protected $fillable = ['employee_name', 'position', 'email'];
}
