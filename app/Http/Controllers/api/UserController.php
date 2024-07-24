<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\loginRequest;
use App\Http\Requests\RegisterUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class UserController extends Controller
{
    public function register(RegisterUser $request)
    {
        try {
           

            // Create new user
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->password_confirmation = bcrypt($request->password_confirmation);
            $user->save();
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Utilisateur ajouté avec succès',
                'Utilisateur' => $user,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de l\'ajout de l\'utilisateur',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function login(loginRequest $request)
    {
     if(auth()->attempt($request->only(['email','password'])))
     {
         $user=auth()->user();
         $token=$user->createToken('auth_token')->plainTextToken;
         return response()->json([
             'status_code' => 200,
             'status_message' => 'Utilisateur connecté avec success',
             'Utilisateur'=>$user,
             'token'=>$token,
         ]);
     }else{
         return response()->json([
             'status_code' => 403,
             'status_message' => 'Emai et/ou mot de passe incorrect',

         ]);
     }
    }
    public function logout(Request $request)
    {
        $user = $request->user();

        if ($user) {
            $user->tokens()->delete(); // Supprime tous les jetons de l'utilisateur

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Déconnexion réussie',
                'user'=>$user,
            ]);
        } else {
            return response()->json([
                'status_code' => 401,
                'status_message' => 'Utilisateur non authentifié'
            ], 401);
        }
    }


}


