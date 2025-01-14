<?php

namespace App\Http\Controllers\Api;

use App\Models\Adress;
use App\Models\Relation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

class AdressController extends Controller
{
    /**
     * Armazena um novo endereço ou associa um usuário a um endereço existente.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    DB::beginTransaction(); // Inicia uma transação no banco de dados

    try {
        // Verifica se o endereço já existe no banco de dados com base no logradouro e no CEP
        if (Adress::where('logradouro', 'like', '%' . $request->logradouro . '%')
            ->where('cep', 'like', '%' . $request->cep . '%')
            ->exists())
        {
            // Recupera o endereço existente
            $adress = Adress::where('cep', 'like', '%' . $request->cep . '%')->firstOrFail();

            // Verifica se já existe uma relação entre o usuário e o endereço
            if (Relation::where('adress_id', $adress->id)
                ->where('user_id', $request->id)
                ->exists())
            {
                // Se a relação já existe, retorna uma mensagem de erro
                return response()->json([
                    'status' => false,
                    'message' => "O endereço já está associado a este usuário.",
                ], 400);
            }

            // Cria uma relação entre o endereço existente e o usuário
            Relation::create(['adress_id' => $adress->id, 'user_id' => $request->id]);
        } else {
            // Cria um novo endereço e um relacionamento com o usuário
            $adress = Adress::create(['logradouro' => $request->logradouro, 'cep' => $request->cep]);
            Relation::create(['adress_id' => $adress->id, 'user_id' => $request->id]);
        }

        DB::commit(); // Confirma a transação

        return response()->json([
            'status' => true,
            'adress' => $adress,
            'message' => "Endereço cadastrado com sucesso!",
        ], 201);
    } catch (Exception $e) {
        DB::rollBack(); // Reverte a transação em caso de erro

        return response()->json([
            'status' => false,
            'message' => "Endereço não cadastrado!",
            "message-error" => $e
        ], 400);
    }
}

    /**
     * Remove um endereço ou apenas o relacionamento com um usuário.
     *
     * @param Request $request
     * @param Adress $adress
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Adress $adress)
    {
        DB::beginTransaction(); // Inicia uma transação no banco de dados
        try {
            // Conta o número de usuários relacionados ao endereço
            $relatedUsersCount = Relation::where('adress_id', $adress->id)->count();

            if ($relatedUsersCount > 1) {
                // Se houver mais de um relacionamento, apenas remove o vínculo com o usuário especificado
                Relation::where('adress_id', $adress->id)
                    ->where('user_id', $request->user_id)
                    ->delete();
            } else {
                // Caso contrário, remove o relacionamento e exclui o endereço
                Relation::where('adress_id', $adress->id)->delete();
                $adress->delete();
            }

            DB::commit(); // Confirma a transação

            return response()->json([
                'status' => true,
                'message' => "Endereço tratado com sucesso!",
            ], 200);

        } catch (Exception $e) {
            DB::rollBack(); // Reverte a transação em caso de erro

            return response()->json([
                'status' => false,
                'message' => "Erro ao tratar endereço!",
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}
