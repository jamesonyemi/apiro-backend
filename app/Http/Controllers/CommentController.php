<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    //
    public function updatePostComment(Request $request, $comment_id, $user_id)
    {

        # code...
        $user_comment  = $this->getUserCommentByCommentIdAndUserId($comment_id, $user_id);

        if( $user_comment->isEmpty() ){
            return response()->json(['message' => "data not available."],200);
        }

        if( empty($request->text) ){
            return response()->json(['error' => "text is required"],200);
        }

        $comment = Comment::find($user_comment[0]->id);
        $comment->whereId($comment->id)
        ->update([
            "text" => $request->text,
        ]);

        return response()->json(['message' => "Comment successfully updated."],200);

    }

    public function deletePostComment(Request $request, $comment_id, $user_id)
    {

        # code...
        $comment  =  $this->getUserCommentByCommentIdAndUserId($comment_id, $user_id);

        if( $comment->isEmpty() ){
            return response()->json(['message' => "data not available."],200);
        }

        $comment->delete();
        return response()->json(['message' => "Comment successfully deleted."],200);

    }

    public function getUserCommentByCommentIdAndUserId($comment_id,$user_id)
    {
        # code...
        return Comment::where([
                ["id", "=", $comment_id],
                ["user_id", "=", $user_id],
            ])->get();
    }
}