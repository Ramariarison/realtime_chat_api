<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User as ModelUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Récupérer tous les utilisateurs de la base de données
    public function getUsers() {
        
        $users = ModelUser::where('role', 'normal')->get();

        return response()->json([
            'success' => true,
            'message' => 'User list retrieved successfully',
            'data' => UserResource::collection($users)
        ]);
    }

    // Stats users
    public function usersStats() {

        $sumUsers = ModelUser::where('role', 'normal')->count();

        $sumValidatedUsers = ModelUser::where('status', 1)->where('role', 'normal')->count();

        $sumPendingUsers = ModelUser::where('status', 0)->where('role', 'normal')->count();

        return response()->json([
            'success' => true,
            'sumUsers' => $sumUsers,
            'sumValidatedUsers' => $sumValidatedUsers,
            'sumPendingUsers' => $sumPendingUsers
        ]);
    }

    // Ajouter un utilisateur (compte)
    public function addUser(Request $request) {
        //
    }

    // Valider un compte
    public function validateUser(ModelUser $user) {

        $user->update([
            'status' => true
        ]);

        return response()->json([
            'message' => 'Account validated successfully'
        ]);

    }

    // Rejeter une demande de validation
    public function removeUser(ModelUser $user) {

        // Supprimer avatar
        if(
            $user->avatar &&
            Storage::disk('public')->exists($user->avatar)
        ) {

            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();
        
        return response()->json([
            'message' => 'User deleted successfully'
        ]);

    }
}
