<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Adress;
use App\Models\Relation;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Lista usuários com a possibilidade de filtragem.
     * Permite filtrar por nome, e-mail, CPF e datas de criação (início e fim).
     * Inclui os perfis associados ao usuário.
     */
    public function index(Request $request)

    {

        if ($request) {

            $usersWithProfile = User::when($request->has('name'), function ($whenQuery) use ($request) {
                $whenQuery->where('name', 'like', '%' . $request->name . '%');
            })
                ->when($request->has('email'), function ($whenQuery) use ($request) {
                    $whenQuery->where('email', 'like', '%' . $request->email . '%');
                })
                ->when($request->has('cpf'), function ($whenQuery) use ($request) {
                    $whenQuery->where('cpf', 'like', '%' . $request->cpf . '%');
                })
                ->when($request->filled('data_inicio'), function ($whenQuery) use ($request) {
                    $whenQuery->where('created_at', '>=', \Carbon\Carbon::parse($request->data_inicio)->format('Y-m-d'));
                })
                ->when($request->filled('data_fim'), function ($whenQuery) use ($request) {
                    $whenQuery->where('created_at', '<=', \Carbon\Carbon::parse($request->data_fim)->format('Y-m-d'));
                })
                ->orderBy('id')
                ->with('profile')
                ->get();
        } else {

            $usersWithProfile = User::with('profile')->get();
        }
        return $usersWithProfile;
    }

    /**
     * Cria um novo usuário e associa um endereço.
     * Caso o endereço já exista, cria apenas o relacionamento.
     * Caso contrário, cria o endereço e o relacionamento.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string|size:14|unique:users,cpf',
            'logradouro' => 'required|string|max:255',
            'cep' => 'required|string|size:9',
        ]);

        // Retorna erros de validação caso existam
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação!',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {
            // Cria o usuário
            $user  = User::create($request->all());

            // Verifica se o endereço já existe
            if (Adress::where('logradouro', 'like', '%' . $request->logradouro . '%')
                ->where('cep', 'like', '%' . $request->cep . '%')
                ->exists()
            ) {
                // Recupera o endereço existente e cria o relacionamento
                $adress = Adress::where('cep', 'like', '%' . $request->cep . '%')->firstOrFail();
                $relation = Relation::create(['adress_id' => $adress->id, 'user_id' => $user->id]);
            } else {
                // Cria um novo endereço e o relacionamento
                $user->adress()->create($request->all());
            }

            // Operação é concluída com êxito
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário cadastrado com sucesso!",
            ], 201);
        } catch (Exception $e) {

            DB::rollBack();

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuário não cadastrado!",
                "message-error" => $e
            ], 400);
        }
    }

    /**
     * Retorna os dados de um usuário específico, incluindo perfil e endereços.
     */
    public function show(User $user)
    {

        $user = User::with('profile')
            ->with('adress')
            ->find($user->id);

        return $user;
    }

    /**
     * Recupera os dados de um usuário para edição.
    */
    public function edit(User $user)
    {

        $user = User::findOrFail($user->id);
        return  $user;
    }

     /**
     * Atualiza os dados de um usuário existente.
     */
    public function update(Request $request, User $user)
    {
        // Valida os dados enviados na requisição
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'cpf' => 'required|string|size:14|unique:users,cpf,' . $user->id,
            'profile_id' => 'nullable|exists:profiles,id',
        ]);

        // Retorna erros de validação caso existam
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação!',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();

        try {

            // Editar o registro no banco de dados
            // Atualiza os dados do usuário
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'cpf' => $request->cpf,
                'profile_id' => $request->profile_id,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Retorna os dados do usuário editado e uma mensagem de sucesso com status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário editado com sucesso!",
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuário não editado!",
                "message-error" => $e
            ], 400);
        }
    }

     /**
     * Remove um usuário e seus relacionamentos com endereços.
     */
    public function destroy(User $user)
    {

        DB::beginTransaction();
        try {

            // apaga os relacionamentos da tabela endereços
            Relation::where('user_id', $user->id)->delete();

            // Apagar o registro no banco de dados
            $user->delete();

            // Retorna os dados do usuário apagado e uma mensagem de sucesso com status 200
            DB::commit();

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário apagado com sucesso!",
            ], 200);
        } catch (Exception $e) {

            DB::rollBack();

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => false,
                'message' => "Usuário não apagado!",
                "message-error" => $e
            ], 400);
        }
    }
}
