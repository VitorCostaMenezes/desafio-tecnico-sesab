<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    public $timestamps = false;
    protected $dates = ['date'];
    protected $fillable = [
        'adress_id',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function adress() {
        return $this->belongsTo(User::class);
    }
}
