<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Routing\Controller as BaseController;
use Firebase\JWT\JWT;

class UserController extends Controller
{
    
	public function store(Request $request)
	{
	
		$user = new User();
        $key = $this->key;

        if ($request->name==null or $request->email==null or $request->password==null or $request->passwordConfirm==null) 
        {
            return response()->json([
               'RESPONSE' => 'Hay campos vacíos', 204
            ]);
        }   
        if (strlen($password = $_POST['password']) < 8)
        {
            return response()->json([
               'RESPONSE' => 'La contraseña tiene que tener al mínimo 8 caracteres', 411
            ]);
        } 


        $users = User::where('email', $request->email)->get();
        foreach ($users as $key => $value) 
        {
            if ($request->email == $value->email) 
            {
               return response()->json([
               'RESPONSE' => 'El usuario ya existe', 400
            ]);
            }
        }


        if (isset($_POST['email']) == true && empty($_POST['email']) == false)
        {
            $email = $_POST['email'];
            if(filter_var($email, FILTER_VALIDATE_EMAIL) == true)
            {
               // ok
            }else
            {
             return response()->json([
               'RESPONSE' => 'Mail no válido', 415
            ]);
            }
        }

        if($_POST["passwordConfirm"] == $_POST["password"])
        {  
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = encrypt($request->password);

                    
            $user->role_id = 2;
            $user->save();
            
            $tokenParams = [ 
            'user' => $user,
            ];

            $token = JWT::encode($tokenParams, $key);
            return response()->json([
               'RESPONSE' => 'El usuario ha sido registrado correctamente', 200
            ]);
        
        }

        else
        {
            return response()->json([
               'RESPONSE' => 'Las contraseñas no coinciden', 403
            ]);

		}

	}

public function index()
    {
        $header = getallheaders();
        $userParams = JWT::decode($header['Authorization'], $this->key, array('HS256'));
        if ($userParams->id == 1) {
            return User::where('role_id', 2)->get();
        }
        else
        {
            return response()->json([
                'RESPONSE' => 403, 'No tiene permiso para hacer esta acción'
            ]);
        }
        
    }

public function update(Request $request, User $user)
    {
        $header = getallheaders();

        if ($header['Authorization'] != null) 
        {
            try {
                $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
                if ($userLogged->id == 1) 
                {
                    if (empty($request->name) || empty($request->email) || empty($request->password))
                    {
                        return response()->json([
                            'RESPONSE' => 'Los campos no pueden estar vacíos', 400
                        ]);
                    }

                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = encrypt($request->password);
                    

                    if (strlen($request->password) < 8)
                    {
                        return response()->json([
                        'RESPONSE' => 'La contraseña tiene que tener al menos 8 caracteres', 411
                        ]);
                    } else {
                       $user->password = encrypt($request->password);
                        
                    }

                    if(filter_var($user->email, FILTER_VALIDATE_EMAIL) == true){
             
                    }else{
                        return response()->json([
                        'RESPONSE' => 'Mail no válido', 415
                    ]);
                    }

                    $user->save();
                    return response()->json([
                        'RESPONSE' => 'El usuario se ha actualizado correctamente', 200
                    ]);


                }   else {
                        return response()->json([
                        'RESPONSE' => 'No tiene permiso para hacer esta acción', 403
                    ]);
                }
            } 
            catch (Exception $e) 
            {
                return response()->json([
                    'RESPONSE' => 'No tiene permiso para hacer esta acción', 403
                ]);
            }
        }
    }

public function destroy(User $user)     
    {
        $header = getallheaders();

        if ($header['Authorization'] != null) 
        {
            try {
                $userLogged = JWT::decode($header['Authorization'], $this->key, array('HS256'));
                if ($userLogged->id == 1) 
                {
                    $user->delete();
                    return response()->json([
                        'RESPONSE' => 'El usuario ha sido eliminado satisfactoriamente', 200
                    ]);
                } else {
                    return response()->json([
                        'RESPONSE' => 'No tiene suficientes permisos para realizar esta acción', 403
                    ]);
                }
            } 
            catch (Exception $e) 
            {
                return response()->json([
                    'RESPONSE' => 'No tiene suficientes permisos para realizar esta acción', 403
                ]);
            }
        }
    }

}

