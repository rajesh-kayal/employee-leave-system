<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql_second';
    protected $table = 'location';
    protected $primaryKey = 'location_id';
    public $timestamps = false;

    protected $fillable = [
        'location',
        'locationName'
    ];
}
