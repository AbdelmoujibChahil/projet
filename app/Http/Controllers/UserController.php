<?php

namespace App\Http\Controllers;

use App\Models\User;
 
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
class UserController extends Controller
{
    public function updatename(Request $request,$id){
          $user = User::findorFail($id);
          $request->validate([
            'name' => ['required', 'string', 'max:255'],
          ]);
          $user->update(['name' => $request->name]);
       
        return response()->json([
            'message' => 'Nom mis à jour avec succès',
            'user' => $user
        ]);   
     }
        public function updateemail(Request $request,$id){
          $user = User::findorFail($id);
          $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
          ]);
          $user->update(['email' => $request->email]);
       
        return response()->json([
            'message' => 'email mis à jour avec succès',
            'user' => $user
        ]);   
     }
        public function updatepassword(Request $request,$id){
          $user = User::findorFail($id);
          $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
          ]);
          $user->update(['password' => Hash::make($request->password)]);
       
        return response()->json([
            'message' => 'password mis à jour avec succès',
            'user' => $user
        ]);   
     }
}
