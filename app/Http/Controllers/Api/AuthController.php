<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        // if ($data->fails()) {
        //     return response()->json($data->errors(), 422);
        // }
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return response()->json(['success' => true, 'msg' => 'User registered successfully'], 201);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            /** @var User $user */
            $user = Auth::user();
            $token = $user->createToken('main')->plainTextToken;
            $success = true;
            $msg = 'Login success!';
            return response(compact('user', 'token', 'success', 'msg'));
        } else {
            return response()->json(['success' => false, 'msg'=>'Unauthorized']);
        }
    }
}
