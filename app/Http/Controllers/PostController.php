<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Pdo\Firebird;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostController extends Controller
{
    public function get_post($id){
        try{
            $post = Posts::where('id', $id)->firstOrFail();
            return response()->json([
                'status'=>'success',
                'data'=>$post // logical error....is not returning the updated data
            ],200);
        }catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later.'
            ],500);
        }
    }
    public function create_post(Request $request){
        try{
            $request->validate([
                'content'=>'string|max:500',
                'image_url'=>'string',
            ]);
            $request['user_id'] = $request->user()->id;
            $post = Posts::create($request->all());
            return response()->json([
                'status'=>'success',
                'data'=>$post
            ],201);
        }
        catch(ValidationException $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Validation Error: ' . $e->getMessage()
            ],422);
        }catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later.'
            ],500);
        }
    }
    public function update_post(Request $request, $id){
        try{
            $post = Posts::where('id', $id);
            if(auth()->user()->id != $post->first()->user_id){
                return response()->json(['status'=>'failed', 'message'=>'unauthorized'], 401);
            }
            $request->validate([
                'content'=>'string|max:500',
                'image_url'=>'string',
            ]);
            $post->update($request->all());
            return response()->json([
                'status'=>'success',
                'data'=>$request->all()
            ],200);
        }
        catch(ValidationException $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Validation Error: ' . $e->getMessage()
            ],422);
        }
        catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later.'
            ],500);
        }
    }
    public function delete_post($id){
        try{
            $post = Posts::where('id', $id);
            if(auth()->user()->role === 'admin'){
                $post->delete();
            }
            else if(auth()->user()->id != $post->first()->user_id){
                return response()->json(['status'=>'failed', 'message'=>'unauthorized'], 401);
            }
            $post->delete();
            return response()->json(['status'=>'success','message'=>'Post deleted'],200);
        }catch(Exception $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong, try again later.'
            ],500);
        }
    }
}
