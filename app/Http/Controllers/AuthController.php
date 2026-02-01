<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (! $token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return response()->json([
        'token' => $token,
        'user' => auth()->user(),'status'=>200
    ]);
}
    public function register(Request $request)
    {
   $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ],[
            'name.required'=>'Le nom est obligatoire',
            'email.required'=>'L email est obligatoire',
            'email.email'=>'Le format de l email est invalide',
            'email.unique'=>'Cet email est deja utilise',
            'password.required'=>'Le mot de passe est obligatoire',
            'password.confirmed'=>'La confirmation du mot de passe ne correspond pas',
            

        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth()->login($user);

          return response()->json([
        'token' => $token,
        'user' => auth()->user(),'status'=>200
    ]);
    }

public function logout()
{
    auth()->logout();
    return response()->json(['message' => 'Logged out','status'=>201]);
}


}
