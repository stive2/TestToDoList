<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::orderBy('nom', 'asc')
                            ->get()
                            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	//Validate data
        $data = $request->only('nom', 'email', 'password');
        $validator = Validator::make($data, [
            'nom' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'nom' => $request->nom,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur crée avec succès',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, utilisateur introuvable.'
            ], 400);
        }
        //Validate data
        $data = $request->only('nom', 'email', 'password');
        $validator = Validator::make($data, [
            'nom' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 500);
        }

        //Request is valid, update user
        $user = $user->update([
            'nom' => $request->nom,
            'email' => $request->email,
        ]);

        //User updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Désolé, utilisateur introuvable.'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé.'
        ], Response::HTTP_OK);
    }
}
