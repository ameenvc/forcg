<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\GovernanceHubDraftComment;
use App\GovernanceHub;
use App\Admin;
use App\GovernanceHubDraft;
use App\User;
use App\Http\Requests\Document\StoreDraftCommentRequest;
use Illuminate\Support\Facades\Auth;


class GovernanceHubDraftCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }

    public function store(StoreDraftCommentRequest $request)
    {
        $comment = new GovernanceHubDraftComment;
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
        $comment->commentable_type = 'App\GovernanceHubDraft';
        $comment->record_id = $user_id.$request->input('document_id').date('s').uniqid();
        $comment->created_at = now();
        $comment->updated_at = now();
        $comment->save();

        $doc = GovernanceHubDraft::findOrFail($request->input('document_id'));
        $doc->comments()->save($comment);

        return response()->json([
            'status'=>200,
            'data'=>null,
           ]);
    }

    protected function getusername($email){
        $user = User::where('email',$email)->value('name');
        $user_name = $user?$user:$email;
        return $user_name;
    }
    public function destroy(int $id)
    {
        $comment = GovernanceHubDraftComment::where('is_deleted', false)->findOrFail($id);

        foreach ($comment->replies as $reply) {
            $reply->where('is_deleted', false)->where('parent_id', $comment->id)->update([
                'is_deleted' => true,
            ]);
        }

        GovernanceHubDraftComment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }

    public function deleteReply(int $id)
    {
        GovernanceHubDraftComment::where('is_deleted', false)->where('id', $id)->update([
            'is_deleted' => true,
        ]);

        return [
            'data' => null,
            'error' => null,
        ];

    }
}
