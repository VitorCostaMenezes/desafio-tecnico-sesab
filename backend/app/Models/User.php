<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'profile_id'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Define a relação "belongsToMany" entre o modelo User e Adress.
     *
     * Um usuário pode estar associado a vários endereços,
     * e esta relação é gerida pela tabela intermediária `relations`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function adress() {
        return $this->belongsToMany(Adress::class, 'relations', 'user_id', 'adress_id');
    }

    /**
     * Define a relação "belongsTo" entre o modelo User e Profile.
     *
     * Cada usuário pertence a um único perfil.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile() : BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
