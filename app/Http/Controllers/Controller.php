<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $key = '47asfasdSDgasdgFsd571Hsa2';

    protected function checkLogin($email, $password)
    {
        $userSave = User::where('email', $email)->first();
        if ($userSave == null)
        {
            return response()->json([
            	'MESSAGE' => 401, 'This user does not exist'
            ]);
            //Entra pero no corta la ejecución
        }
        $emailSave = $userSave->email;
        $passwordSave = $userSave->password;
        $passwordSave = decrypt($passwordSave);
        if ($emailSave == $email and $passwordSave == $password)
        {
            return true;
        }
        return false;
    }
}
