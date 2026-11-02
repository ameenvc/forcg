<?php

namespace App\Http\Controllers;

use App\GovernanceHubComment;
use App\GovernanceHub;
use App\Admin;
use App\User;
use App\Http\Requests\Document\StoreCommentRequest;
use Illuminate\Support\Facades\Auth;

class GovernanceHubCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function store(StoreCommentRequest $request)
    {

        $comment = new GovernanceHubComment();

        $comment->comment = $request->input('comment');

        $is_admin_id = Auth::user()->id;

        $admin = Admin::find($is_admin_id);

        $user_id = null;

        if($admin){

            $user = User::where('email',$admin->email)->first();

            $user_id = $user ? $user->id : Auth::user()->id;

        }else{

            $user_id = Auth::user()->id;

        }
        $comment->user_id = $user_id;

        $comment->commentable_id = $request->input('document_id');

        $comment->parent_id = $request->input('reply_id') ?? 0;

        $comment->commentable_type = 'App\GovernanceHub';

        $comment->created_at = now();

        $comment->updated_at = now();

        $comment->save();

        // $doc = GovernanceHub::where('is_deleted', false)->findOrFail($request->input('document_id'));
        $doc = GovernanceHub::findOrFail($request->input('document_id'));

        $doc->comments()->save($comment);

        return [
            'data' => null,
            'error' => null,
        ];
    }

    public function destroy(int $id)
    {
        $comment = GovernanceHubComment::where('is_deleted', false)->findOrFail($id);

        foreach ($comment->replies as $reply) {
            $reply->where('is_deleted', false)->where('parent_id', $comment->id)->update([
                'is_deleted' => true,
            ]);
        }

        GovernanceHubComment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function deleteReply(int $id)
    {
        GovernanceHubComment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }
}
