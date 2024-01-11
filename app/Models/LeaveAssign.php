<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveAssign extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $table = 'leave_assign';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employeeid',
        'leave_type',
        'leave_count',
        'is_active',
        'is_deleted',
    ];
}
