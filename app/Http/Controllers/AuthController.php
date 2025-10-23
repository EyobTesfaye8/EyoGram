<?php

namespace App\Http\Controllers;

use PhpParser\Builder\FunctionLike;
use Throwable;
use App\Models\User;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $request->validate([
                'first_name'=>'required|string|min:3',
                'last_name'=>'required|string|min:3',
                'email'=>'required|string|email',
                'password'=>'required|string|min:6'
            ]);

            $user = User::create($request->all());
            return response()->json([
                'status'=>'success',
                'data'=>$user->except(['created_at','updated_at'])
            ],201);
        }catch(ValidationException $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Validation error: '. $e->getMessage()
            ],422);
        }catch(Throwable $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later'
            ]);
        }
    }
    public function login(Request $request){
        try{
            $request->validate([
                'email'=>'required|string',
                'password'=>'required|string'
            ]);
            $credentials = $request->only(['email', 'password']);
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status'=>'failed',
                    'error' => 'Invalid credentials'], 400);
            }
            return response()->json([
                'token'=>$token,
                'token_type'=>'bearer',
                'expires_in'=>auth()->factory()->getTTL()/24 . ' hours.'

            ],200);
        }catch(ValidationException $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Validation error: '. $e->getMessage()
            ],422);
        }catch (JWTException $e) {
            return response()->json([
                'status'=>'failed',
                'error' => 'Could not create token'], 500);
        }catch(Throwable $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later' . $e->getMessage()
            ]);
        }
    }
    public function logout(Request $request){
        try{
            if($request->user()->id === auth()->user()->id){
                auth()->logout();
                return response()->json([
                    'status'=>'success',
                    'message'=>'Logged out'
                ],200);
            }
            else{
                return response()->json([
                'status'=>'failed'
            ],401);
            }
        }catch(Throwable $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later'
            ]);
        }
    }
    public function delete_account(Request $request){
        try{
            if(!($request->user()->id === auth()->user()->id || $request->user()->role === 'admin')){
                return response()->json(['status'=>'failed'],401);
            }
            $deleted_user = User::find($request->user()->id)->delete();
            return response()->json([
                'status'=>'success',
                'message'=>'User deleted successfully.'
            ],200);
        }catch(Throwable $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later'
            ]);
        }
    }
}