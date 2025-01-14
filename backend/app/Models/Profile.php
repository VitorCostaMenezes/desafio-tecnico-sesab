<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $dates = ['date'];
    protected $fillable = [
        'profile_name'
    ];

    /**
     * Define a relação "hasMany" entre o modelo Profile e User.
     *
     * Esta relação indica que um perfil pode estar associado a múltiplos usuários.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user() : HasMany
    {
        return $this->hasMany(User::class); // Um perfil pode estar associado a vários usuários
    }

}
