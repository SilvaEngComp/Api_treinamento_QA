<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $dados = $request->all();

        $testPassword = User::isStrongPassword($dados["password"]);

        if (!$testPassword["status"]) {
            return $this->error($testPassword["message"], 400);
        }

        $testCpf = User::isCpfValid($dados["cpf"]);

        if (!$testCpf["status"]) {
            return $this->error($testCpf["message"], 400);
        }
        try {
            if (!User::with('email', $dados["email"])->exists()) {

                $user =  User::create([
                    "name" => $dados["name"],
                    "email" => $dados["email"],
                    "password" => $dados["password"],
                    "cpf" => $dados["cpf"],
                    "cnpj" => $dados["cnpj"],
                ]);

                return $this->success("Cadastro realizado com sucesso", $user);
            }
            return  $this->error("O Email enviado já está cadastrado", 400, $dados["email"]);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
