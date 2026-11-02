<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Course;
use App\Http\Requests\Comment\StoreCommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function store(StoreCommentRequest $request)
    {

        $comment = new Comment;

        $comment->comment = $request->input('comment');

        $comment->user_id = Auth::user()->id;

        $comment->commentable_id = $request->input('course_id');

        $comment->parent_id = $request->input('reply_id') ?? 0;

        $comment->commentable_type = 'App\Course';

        $comment->created_at = now();

        $comment->updated_at = now();

        $comment->save();

        $course = Course::where('is_deleted', false)->findOrFail($request->input('course_id'));

        $course->comments()->save($comment);

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function destroy(int $id)
    {
        $comment = Comment::where('is_deleted', false)->findOrFail($id);

        foreach ($comment->replies as $reply) {
            $reply->where('is_deleted', false)->where('parent_id', $comment->id)->update([
                'is_deleted' => true,
            ]);
        }

        Comment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function deleteReply(int $id)
    {
        Comment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

}
