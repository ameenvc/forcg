<div class="media {{ isset($isReply) && $isReply ? 'mt-2' : 'push' }}" style="padding-bottom:6px;margin-bottom:0;" id="{{ isset($isReply) && $isReply ? 'comment-reply'.$comment->id : 'comment'.$comment->id }}">
    <a class="mr-3" href="javascript:void(0)">
        @if($comment->admin)
            <img class="img-avatar img-avatar32"
                src="{{ $comment->admin->profile_picture ? '/storage/profile/' . $comment->admin->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                alt="">
        @elseif($comment->user)
            <img class="img-avatar img-avatar32"
                src="{{ $comment->user->profile_picture ? '/storage/profile/' . $comment->user->profile_picture : asset('media/avatars/avatar3.jpg') }}"
                alt="">
        @endif
    </a>
    @php 
        $userId = App\User::where('email',Auth::User()->email)->value('id');
    @endphp
    <div class="media-body">
        <a class="font-w600" href="javascript:void(0)">
            {{ $comment->admin ? ucfirst($comment->admin->name) : ucfirst($comment->user->name) }}
        </a>
        <span id="commenttext{{$comment->id}}">{{--@if($comment->created_at!=$comment->updated_at)<i class="fa fa-2x fa-pencil-alt" style="font-size:11px;"></i>@endif--}}{!! $comment->comment !!}</span>
        {{-- <a class="mr-1" href="javascript:void(0)"><span style="font-size:11px;">Like</span></a> --}}
        @if($comment->user->id==$userId)
        <a class="mr-1" href="javascript:void(0)" onclick="editComment('{{ $comment->id }}','{{json_encode($comment->comment)}}')"><span style="font-size:11px;">Edit</span></a>
        <a class="mr-1" href="javascript:void(0)"
            onclick="{{ isset($isReply) && $isReply ? 'deleteReply' : 'deleteComment' }}('{{ $comment->id }}')">
            <span style="font-size:11px;">Delete</span>
        </a>
        @endif
        @if(!isset($isReply) || !$isReply)
            <a class="mr-1"  onclick="reply('{{ $comment->id }}')">
                <span style="font-size:11px;color:#5179d6;cursor:pointer;">Reply</span>
            </a>
        @endif
        <span class="text-muted">
            <em><span style="font-size:11px;" id="updatedat{{$comment->id}}">{{ $comment->updated_at->diffForHumans() }}</span></em>
        </span>

        <!-- Nested replies for top-level comments -->
        @if(!isset($isReply) || !$isReply)
            @foreach($comment->replies as $reply)
                @include('user.risks._comment', ['comment' => $reply, 'isReply' => true])
            @endforeach
        @endif
        <div id="replysection{{$comment->id}}"></div>
    </div>
</div>
