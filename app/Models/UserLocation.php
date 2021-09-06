<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    use HasFactory;
    public $timestamps=true;
protected $table='user_locations';
    protected $fillable=[
        'latitude',
        'longitude',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
