<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginIpDetail extends Model
{
    use HasFactory;

    protected $table = 'loginipdetails';
    protected $primaryKey = 'staffattendanceipid';
    protected $fillable = [
        'employeeid',
        'emplastname',
        'empfirstname',
        'empLocation',
        'empattendancestatus',
        'empattendancedate',
        'loginip',
        'entrytimestamp'
    ];
}
