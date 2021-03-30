<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;

class CommentsController extends Controller
{
    public function addcomment(Request $request)
    {	
    	$this->validate($request, [
           'username' => 'required',
           'comment' => 'required',
        ]);

        $create = Comment::create($request->all());

    	return response()->json(['comment' => $request->all()], 200);
    	
    }

    public function getcomments()
    {
    	$comments = Comment::where('parent_id', '=', NULL)->orderBy('created_at', 'desc')->get();
        return response()->json($comments);
    }

    public function replycomment(Request $request, $comment_id)
    {
    	$this->validate($request, [
           'username' => 'required',
           'comment' => 'required',
        ]);

        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->username = $request->username;
        $comment->parent_id = $comment_id;
        
        $comment->save();

        $parent_comment = Comment::find($comment_id);
        $parent_comment->increment('counter');

        return response()->json(['comment' => $request->all()], 200);

    }

    public function getreplies($comment_id)
    {
    	$comments = Comment::where('parent_id', '=', $comment_id)->orderBy('created_at', 'desc')->get();
        return response()->json($comments);
    }
}
