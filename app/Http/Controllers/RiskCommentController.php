<?php

namespace App\Http\Controllers;

use App\RiskComment;
use App\Risk;
use App\Admin;
use App\User;
use App\Http\Requests\Risk\EditCommentRequest;
use App\Http\Requests\Risk\StoreCommentRequest;
use Illuminate\Support\Facades\Auth;

class RiskCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
    }
    public function store(StoreCommentRequest $request)
    {

        try {
            $comment = new RiskComment();
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
            $comment->commentable_id = $request->input('risk_id');
            $comment->parent_id = $request->input('reply_id') ?? 0;
            $comment->commentable_type = 'App\Risk';
            $comment->created_at = now();
            $comment->updated_at = now();
            $doc = Risk::where('Rid',$request->input('risk_id'))->first();
            $doc->comments()->save($comment);
            $totalcomments = RiskComment::where('commentable_id',$request->input('risk_id'))->where('is_deleted',false)->count();
            $user = User::where('email',Auth::User()->email)->first();
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $comment->id,
                    'comment' => htmlspecialchars($comment->comment, ENT_QUOTES, 'UTF-8'),
                    'parent_id' => $comment->parent_id,
                    'user_name' => ucfirst($user->name),
                    'created_at' => $comment->updated_at->diffForHumans(),
                    'user_id' => $comment->user_id,
                    'profile_picture' => $user->profile_picture ? '/storage/profile/'.$user->profile_picture : asset('media/avatars/avatar3.jpg'),
                    'is_reply' => $request->input('reply_id')?true:false,
                    'totalcomments' => $totalcomments
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $comment = RiskComment::where('is_deleted', false)->findOrFail($id);
            $risk_id = $comment->commentable_id;
            foreach ($comment->replies as $reply) {
                $reply->where('is_deleted', false)->where('parent_id', $comment->id)->update([
                    'is_deleted' => true,
                ]);
            }
            RiskComment::where('is_deleted', false)->where('id', $id)->update([
                'is_deleted' => true,
            ]);
            $totalcomments = RiskComment::where('commentable_id',$risk_id)->where('is_deleted',false)->count();
            return response()->json([
                'success' => true,
                'totalcomments' => $totalcomments
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ]);
        }

    }

    public function deleteReply(int $id)
    {
        try {
            $comment = RiskComment::where('is_deleted', false)->findOrFail($id);
            $risk_id = $comment->commentable_id;
            RiskComment::where('is_deleted', false)->where('id', $id)->update([
                'is_deleted' => true,
            ]);

            $totalcomments = RiskComment::where('commentable_id',$risk_id)->where('is_deleted',false)->count();
            return response()->json([
                'success' => true,
                'totalcomments' => $totalcomments
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ]);
        }

    }
    public function editComment(EditCommentRequest $request)
    {
        try {
            $risk_comment = RiskComment::findOrFail($request->input('comment_id'));
            $risk_comment->update([
                'comment' => $request->input('comment'),
                'updated_at' => now()
            ]);
            return response()->json([
                'success' => true,
                'data' => [
                    'comment' => $risk_comment->comment,
                    'created_at' => $risk_comment->updated_at->diffForHumans(),
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => $th->getMessage(),
            ]);
        }
    }
}
