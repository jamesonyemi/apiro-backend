<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    //
    public function index(Request $request)
    {

        $post = Post::query()->with("comments")->orderBy("created_at", "desc");

        if ( $request->q !== '' ) {
            $post->where('title', 'LIKE', "%$request->q%");
            $post->orWhere('text', 'LIKE', "%$request->q%");
        }

        return $post->paginate(15);

    }

    public function store(Request $request, Post $post)
    {
        # code...
        Post::create([
            "title" => $request->title,
            "text" => $request->text,
            "user_id" => $request->user_id,
        ]);

        return response()->json([
            'message' => "Post successfully created."
        ],200);


    }

    public function savePostComment(Request $request, Post $post)
    {
        # code...
        $post = Post::find($request->post_id);

        $post->comments()->whereId($post->id)
        ->create([
            "text" => $request->text,
            "post_id" => $request->post_id,
            "user_id" => $request->user_id,
        ]);

        $this->sendEmailNotification($post->user->email);

        return response()->json([
            'message' => "A new comment successfully created."
        ],200);


    }

    public function updatePost(Request $request, Post $post)
    {
        # code...
        $postComment = Post::with('comments')->find($post);

        if( $postComment->isEmpty() ){
            return response()->json(['message' => "data not available."],200);
        }

        if ( $postComment[0]->comments->isEmpty() ) {
            # code...
            Post::find($post->id)->whereId($post->id)->update([
                "title" => $request->title,
                "text" => $request->text,
            ]);
            return response()->json(['message' => 'Post successfully updated'], 200);

        }

        return response()->json([
            'message' => "This post cannot be edited because it has associated comments."
        ],200);

    }

    public function deletePost(Request $request, Post $post)
    {

        # code...
        $post = Post::with('comments')->find($post);

        if( $post->isEmpty() ){
            return response()->json(['message' => "data not available."],200);
        }

        if ( $post[0]->comments->isEmpty() ) {
            # code...
            Post::find($post->id)->delete();
            return response()->json(['message' => 'Post successfully deleted'], 200);

        }

        return response()->json(['message' => "This post cannot be deleted because it has associated comments."],200);

    }

    public function sendEmailNotification($to)
    {

        sleep(5);
        \Mail::raw('Someone just commented on your post', function ($message) use($to) {
            $message->sender('no-reply@yourmellon.com', 'Your Mellon');
            $message->to($to);
            $message->subject('Subject');
            $message->priority(3);
        });


    }


}