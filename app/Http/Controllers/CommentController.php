<?php

namespace App\Http\Controllers;

use App\Models\Comments;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($Post_id)
    {
        try{
            $comments = Comments::where('post_id', $Post_id)->get(['id', 'content', 'created_at']);
            return response()->json([
                'status'=>'success',
                'data'=>$comments
            ]);
        }catch(Exception $e){
            return response()->json(['status'=>'failed', 'message'=>'Something went wrong'],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'content'=>'required|string',
                'post_id'=>'required|integer|min:0'
            ]);
            $request['user_id'] = auth()->user()->id;
            $comment = Comments::create($request->all());
            return response()->json([
                'status'=>'success',
                'data'=>$comment
            ],201);
        }catch(ValidationException $e){
            return response()->json([
                'status'=>'failed',
                'message'=>'Validation Error: ' . $e->getMessage()
            ],422);
        }catch(Exception){
            return response()->json([
                'status'=>'failed',
                'message'=>'Something went wrong'
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
