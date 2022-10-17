<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\ApiResponser;
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
        return $dados = $request->all();

        return  $testPassword = User::isStrongPassword($dados["password"]);

        if (!$testPassword["status"]) {
            return $this->error($testPassword["message"], 400);
        }
        return 'oi';

        $testCpf = User::isCpfValid($dados["cpf"]);

        if (!$testCpf["status"]) {
            return $this->error($testCpf["message"], 400);
        }

        $testCnpj = User::isCnpjValid($dados["cnpj"]);

        if (!$testCnpj["status"]) {
            return $this->error($testCnpj["message"], 400);
        }



        $user =  User::create([
            "name" => $dados["name"],
            "email" => $dados["email"],
            "password" => $dados["password"],
            "cpf" => $dados["cpf"],
            "cnpj" => $dados["cnpj"],
        ]);

        return $this->success("Cadastro realizado com sucesso", 200, $user);
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
