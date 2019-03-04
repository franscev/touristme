<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Login;
use \Firebase\JWT\JWT;


class LoginController extends Controller
{
    
	public function login()
	{
		$key = $this->key;

		if($_POST["email"] == null or $_POST["password"] == null)
			{
				return response(204);
			}

		$user = User::where('email', $_POST['email'])->first();

		if (empty($user))
		{

			return response(401); // mail no autorizado 
		}

		if($_POST["password"] == decrypt($user->password))
		{	
			

			$tokenParams = [
				'user' => $user,
				'random' => time()
			];
			$token = JWT::encode($tokenParams, $key);
			return response()->json([
				'token'=> $token,
			]);
		}
		else
		{
			return response(400); // Respuesta contraseña incorrecta
		}
	}

	public function loginWeb()
    {
        if (empty($_POST['email']) or empty($_POST['password'])) 
        {
            return response()->json([
                'RESPONSE' => 'Hay campos vacíos'], 411
            );
        } 
        else 
        {
            $user = User::where('email', $_POST['email'])->first();
            if (!empty($user)) 
            {
                if (decrypt($user->password) == $_POST['password']) 
                {
                    if ($user->role_id == 1) 
                    {
                            $tokenParams = [
                            'id' => $user->id,        
                            'password' => $_POST['password'],
                            'email' => $_POST['email']
                        ];

                        $token = JWT::encode($tokenParams, $this->key);
                        return response()->json([
                            'RESPONSE' => $token], 200
                        );
                    }
                    else 
                    {
                        return response()->json([
                            'RESPONSE' =>'No tiene permisos suficientes'], 403
                        );
                    }
                } 
                else 
                {
                    return response()->json([
                        'RESPONSE' => 'La contraseña introducida no es válida'], 400
                    );
                }
            }
            else 
            {
                return response()->json([
                    'RESPONSE' => 'El mail introducido no es válido'], 400
                );
            }
        }
    }
}
