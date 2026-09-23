<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function api_index(){
        $blog=Blog::get();
        return response()->json(['blog'=>$blog]);
    }

    public function api_store(request $request){
        $validatedData = $request->validate([
            'section' => ['nullable'],
            'description' => ['nullable'],
        ]);

        // Create a new blog entry
        $blog = new Blog;
        $blog->section = $validatedData['section'];
        $blog->description = $validatedData['description'];

        // Save the blog entry to the database
        $blog->save();

        // Return a response, such as the newly created blog entry
        return response()->json(['blog' => $blog], 200);
    }

    public function api_update(Request $request, $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'section' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
        ]);

        // Find the blog entry by ID
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog entry not found'], 404);
        }

        // Update the blog entry with validated data
        if (isset($validatedData['section'])) {
            $blog->section = $validatedData['section'];
        }

        if (isset($validatedData['description'])) {
            $blog->description = $validatedData['description'];
        }

        // Save the updated blog entry
        $blog->save();

        // Return a response with the updated blog entry
        return response()->json(['message'=>'update blog successfully','blog' => $blog], 200);
    }
}
