<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $avatarPath = null;

        if ($request->hasFile('avatar')) {

            $avatarPath = $request
                ->file('avatar')
                ->store('avatars', 'public');
        }

        $user = ModelsUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'normal',
            'avatar' => $avatarPath,
        ]);

        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        // Validation de données
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        $credentials = $request->only(['email', 'password']);

        // Attribution du token si credentials valident
        if(! $token = Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Please verify your credentials !'
            ]);
        };

        // Récupération de l'utilisateur
        $user = Auth::user();

        // Vérification statut
        if(! $user->status){
            // Déconnexion
            Auth::logout();

            return response()->json([
                'message' => 'Your account is not valided yet, please wait until the administrator valid it.',
                'user_status' => $user->status,
                'need_validation' => true
            ], 403);
        };

        // Réponse json pour la vue
        return response()->json([
            'message' => 'Connected successfully !',
            'user' => $user,
            'access_token' => $token,
            'type_token' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    // Déconnexion utilisateur
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Disconnected successfully !'
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'avatar' => 'nullable|image|max:1024',
        ]);

        // Vérification mot de passe actuel
        if($request->current_pass && $request->new_pass) {

            if(!Hash::check($request->current_pass, $user->password)) {
                return response()->json([
                    'message' => 'Mot de passe actuel incorrect'
                ], 422);
            }

            $user->password = Hash::make($request->new_pass);
        }

        // Upload avatar
        if($request->hasFile('avatar')) {

            $path = $request->file('avatar')->store('avatars', 'public');

            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()->json([
            'message' => 'Profil mis à jour',
            'user' => $user
        ]);
    }
}