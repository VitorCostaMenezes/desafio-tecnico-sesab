<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adress extends Model
{
    public $timestamps = false;
    protected $dates = ['date'];

    protected $fillable = [
        'logradouro',
        'cep'
    ];

    /**
     * Define a relação "belongsToMany" entre o modelo Adress e User.
     *
     * Esta relação utiliza a tabela intermediária 'relations' para
     * representar o vínculo entre usuários e endereços.
     *
     * Parâmetros do método belongsToMany:
     * - User::class: Modelo relacionado (User).
     * - 'relations': Nome da tabela pivot que define a relação.
     * - 'adress_id': Chave estrangeira local na tabela pivot (referencia Adress).
     * - 'user_id': Chave estrangeira relacionada na tabela pivot (referencia User).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user() {
        return $this->belongsToMany(User::class, 'relations', 'adress_id', 'user_id');
    }
}
