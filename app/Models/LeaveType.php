<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $table = 'leave_type';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',
        'color',
        'isdeleted'
    ];


}
