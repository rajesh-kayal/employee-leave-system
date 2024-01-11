<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplyLeave extends Model
{


    protected $connection = 'mysql_second';
    protected $table = 'apply_leave';
    protected $primaryKey = 'id';
    protected $fillable = [
        'employeeid',
        'assign_id',
        'count',
        'status',
        'start',
        'half_start',
        'half_end',
        'end',
        'approver',
        'reason',
        'reject_reason',
        'rejoin',
    ];

public $timestamps = true;

}
