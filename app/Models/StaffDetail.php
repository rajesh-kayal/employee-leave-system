<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDetail extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'staffdetails';
    protected $primaryKey = 'employeeid';

    protected $fillable = ['emplastname',
     'middleini',
     'empfirstname',
     'emplocation',
     'empstatus',
     'empemail',
     'empManager',
     'empmanageremail'];

}
