<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\PostLike;

class CommentController extends Controller
{
    use HttpResponses;

    public function addComment(StoreCommentRequest $request, $id)
    {

        $post = Post::find($id);

        if(!$post) {
            return $this->error(null, 'Data not found', 404);
        }

        $comment = new Comment();
        $comment->user_id = Auth::id();
        $comment->post_id = $id;
        $comment->text = $request->text;
        $comment->save();

        return $this->success(new CommentResource($comment), 'Successfully added comment');

    }

    public function deleteComment($id)
    {
        $comment  = Comment::find($id);
        if(!$comment) {
            return $this->error(null, 'Data not found', 404);
        }

        if($comment->user_id !== Auth::id()) {
            return $this->error(null, 'You cannot delete others comments', 404);
        }

        $comment->delete();
        return $this->success(null, 'Successfully deleted comment');
    }

    public function toggleReact($id)
    {
        $post = Post::find($id);

        if(!$post) {
            return $this->error(null, 'Data not found', 404);
        }

        $msg = "";
        $findLike = PostLike::where('post_id', $id)->where('user_id', Auth::id())->first();
        if($findLike) {
            $msg = "Successfully remove react to this post";
            $findLike->delete();
        } else {
            $msg = "Successfully gave react to this post";
            $pl = PostLike::create(['user_id' => Auth::id() , 'post_id' => $id]);
        }

        return $this->success(null, $msg);
    }
}
