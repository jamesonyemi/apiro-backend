<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\IsUserEmailVerified;
use App\Http\Middleware\VerifyEmailBeforeAddingComment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/posts/{q?}', [PostController::class, 'index'])->name('posts');
Route::middleware([IsUserEmailVerified::class])->post('/post/store', [PostController::class, 'store'])->name('post.store');
Route::middleware([VerifyEmailBeforeAddingComment::class])->post('/posts/{post_id}/comment', [PostController::class, 'savePostComment'])->name('show-post-to-comment-on');
Route::put('/post/{post}/edit', [PostController::class, 'updatePost'])->name('update-post');
Route::delete('/post/{post}/delete', [PostController::class, 'deletePost'])->name('delete-post');

Route::put('/comment/{comment_id}/user/{user_id}/edit', [CommentController::class, 'updatePostComment'])->name('update-comment');

Route::delete('/comment/{comment_id}/user/{user_id}/delete',[CommentController::class, 'deletePostComment'])->name('delete-comment');