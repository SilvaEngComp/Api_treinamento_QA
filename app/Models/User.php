<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    public static function  factory($user): User
    {
        $localUser = new User();
        if ($user) {
            $localUser->id = $user["id"];
            $localUser->name = $user["name"];
            $localUser->email = $user["email"];
            $localUser->cpf = $user["cpf"];
            $localUser->cnpj = $user["cnpj"];
        }
        return $localUser;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone",
        "cpf",
        "cnpj"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public  function build(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "email" => $this->email,
            "cpf" => $this->cpf,
            "cnpj" => $this->cnpj,
        ];
    }

    public static function isStrongPassword(string $password): array
    {
        try {
            $flag = true;
            $message = "";
            if (strlen($password) < 8) {
                $flag = false;
                $message = "Senha fraca! quantidade de caracteres inferior a 8";
            }

            if (!preg_match('/[A-Z]/', $password)) {
                $flag = false;
                $message = "Senha fraca! Inclua pelo menos uma letra ma??uscula";
            }

            if (!preg_match('/[a-z]/', $password)) {
                $flag = false;
                $message = "Senha fraca! Inclua pelo menos uma letra min??scula";
            }
            if (!preg_match('/[0-9]/', $password)) {
                $flag = false;
                $message = "Senha fraca! Inclua pelo menos um n??mero";
            }

            if (!preg_match('/[\'^??$%&*()}{@#~?><>,|=_+??-]/', $password)) {
                $flag = false;
                $message = "Senha fraca! Inclua pelo menos um caracter especial";
            }
            return ["status" => $flag, "message" => $message];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public static function isCpfValid(string $cpf): array
    {
        $flag = true;
        $message = "";
        // Extrai somente os n??meros
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            $flag = false;
            $message = "CPF inv??lido! quantidade de n??meros diferente de 11";
        }

        // Verifica se foi informada uma sequ??ncia de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $flag = false;
            $message = "CPF inv??lido! Sequ??ncia repetida de n??meros";
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $flag = false;
                $message = "CPF inv??lido! Combina????o impr??pria";
            }
        }
        return ["status" => $flag, "message" => $message];
    }

    public static function isCnpjValid(string $cnpj): array
    {
        $flag = true;
        $message = "";
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

        // Valida tamanho
        if (strlen($cnpj) != 14) {
            $flag = false;
            $message = "CNPJ inv??lido! Quantidade de n??meros diferente de 14";
        }


        // Verifica se todos os digitos s??o iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $flag = false;
            $message = "CNPJ inv??lido! Todos os d??gitos s??o iguais";
        }

        // Valida primeiro d??gito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            $flag = false;
            $message = "CNPJ inv??lido! 12?? digito ?? impr??prio";
        }


        // Valida segundo d??gito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;

            $flag = false;
            $message = "CNPJ inv??lido! Segundo d??gito verificador impr??prio";
        }

        $resto = $soma % 11;

        if ($cnpj[13] == ($resto < 2 ? 0 : 11 - $resto)) {
            $flag = false;
            $message = "CNPJ inv??lido! D??gito de controle impr??prio";
        }

        return ["status" => $flag, "message" => $message];
    }
}
