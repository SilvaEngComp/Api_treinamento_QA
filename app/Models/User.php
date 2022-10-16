<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        "cpf"
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

    public static function isStrongPassword(string $password): array
    {
        $flag = true;
        $message = "";
        if (strlen($password) < 8) {
            $flag = false;
            $message = "Senha fraca! quantidade de caracteres inferior a 8";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $flag = false;
            $message = "Senha fraca! Inclua pelo menos uma letra maíuscula";
        }
        if (!preg_match('/[a-z]/', $password)) {
            $flag = false;
            $message = "Senha fraca! Inclua pelo menos uma letra minúscula";
        }
        if (!preg_match('/[0-9]/', $password)) {
            $flag = false;
            $message = "Senha fraca! Inclua pelo menos um número";
        }
        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            $flag = false;
            $message = "Senha fraca! Inclua pelo menos um caracter especial";
        }
        return ["status" => $flag, "message" => $message];
    }


    public static function isCpfValid(string $cpf): array
    {
        $flag = true;
        $message = "";
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            $flag = false;
            $message = "CPF inválido! quantidade de números diferente de 11";
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $flag = false;
            $message = "CPF inválido! Sequência repetida de números";
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                $flag = false;
                $message = "CPF inválido! Combinação imprópria";
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
            $message = "CNPJ inválido! Quantidade de números diferente de 14";
        }


        // Verifica se todos os digitos são iguais
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            $flag = false;
            $message = "CNPJ inválido! Todos os dígitos são iguais";
        }

        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }

        $resto = $soma % 11;

        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            $flag = false;
            $message = "CNPJ inválido! 12º digito é impróprio";
        }


        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;

            $flag = false;
            $message = "CNPJ inválido! Segundo dígito verificador impróprio";
        }

        $resto = $soma % 11;

        if ($cnpj[13] == ($resto < 2 ? 0 : 11 - $resto)) {
            $flag = false;
            $message = "CNPJ inválido! Dígito de controle impróprio";
        }

        return ["status" => $flag, "message" => $message];
    }
}
