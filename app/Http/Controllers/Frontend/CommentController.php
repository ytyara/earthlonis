<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Place;

class CommentController extends Controller
{
    public function store(Request $request, Place $place)
    {
        $request->validate([
            'name'      => 'required|string|max:100',
            'body'      => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        Comment::create([
            'place_id'    => $place->id,
            'parent_id'   => $request->parent_id,
            'name'        => $request->name,
            'body'        => $request->body,
            'is_approved' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your comment has been submitted and is awaiting moderation.'
        ]);
    }
}
