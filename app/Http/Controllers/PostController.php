<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{
    //
    public function index()
    {
        $posts = Post::latest()->get();

        return response()->json([
            'success'   => true,
            'message'   => 'List Data Post',
            'data'      => $posts
        ],200);
    }

    public function show($id)
    {
        // mencari post berdasarkan id
        $post = Post::findOrfail($id);

        // membuat respon JSON
        return response()->json([
            'success'   => true,
            'message'   => 'Detail Data Post',
            'data'      => $post
        ], 200);
    }

    public function store(Request $request)
    {
        // set validasi
        $validator = Validator::make($request->all(),[
            'title'     => 'required',
            'content'   => 'required'
        ]); 


        // validasi error
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // menyimpan ke database
        $post = Post::create([
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //berhasil menyimpan ke database
        if($post) {

            return response()->json([
                'success'   => true,
                'message'   => 'Data berhasil tersimpan',
                'data'      => $post
            ],201);

        }

        //gagal menyimpan ke database
        return response()->json([
            'success'   => false,
            'message'   => 'Penyimpanan gagal'
        ], 409);

    }

    public function update(Request $request, Post $post)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'title'   => 'required',
            'content' => 'required',
        ]);

        //response error validation
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //find post by ID
        $post = Post::findOrFail($post->id);

        if ($post) {

            //update post
            $post->update([
                'title'     => $request->title,
                'content'   => $request->content
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post Updated',
                'data'    => $post
            ], 200);
        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return void
     */
    public function destroy($id)
    {
        //find post by ID
        $post = Post::findOrfail($id);

        if ($post) {

            //delete post
            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post Deleted',
            ], 200);
        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Post Not Found',
        ], 404);
    }
}
