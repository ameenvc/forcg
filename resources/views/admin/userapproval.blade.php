@extends('admin.nav')

@section('content')


<!doctype html>
<html lang="en">
<!-- Main Container -->
<main id="main-container">
    <!-- Hero -->
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content" style="width:92%;margin-top:-75px">
        <!-- Full Table -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">pending approvals</h3>
                <div class="block-options"></div>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    @if(count($users)>0)
                    <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                        <thead>
                            <tr>
                                <th class="text-left" style="width:5%;font-size:10px">Name</th>
                                <th class="text-left" style="font-size:10px">Email</th>
                                <th class="text-left" style="width: 15%;font-size:10px">Approve Status</th>
                                <th class="text-left" style="width: 15%;font-size:10px">Email Verification Status</th>
                                <th class="text-left" style="width: 5%;font-size:10px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $number = 1; ?>
                            <?php
                            // Sort the users array so that verified users appear first
                            $sortedUsers = $users->sort(function ($a, $b) {
                                $aVerified = App\User::where('email', $a->email)->where('is_email_verified', 1)->exists();
                                $bVerified = App\User::where('email', $b->email)->where('is_email_verified', 1)->exists();
                                return $bVerified - $aVerified;
                            });
                            ?>
                            @foreach($sortedUsers as $user)
                            {{csrf_field()}}
                            <tr>
                                <td style="width:5%;font-size:10px" class="text-left">{{$user->name}}</td>
                                <td style="width: 15%;font-size:10px" class="text-left">{{$user->email}}</td>
                                <td style="width: 15%;font-size:10px" class="text-left">Not Approved</td>
                                <td style="width: 15%;font-size:10px" class="text-left">{{$user->is_email_verified === 1 ? 'Verified' : 'Not Verified'}}</td>
                                <td>
                                    @php
                                    // Query the user table to check if the user's email is verified
                                    $is_verified = App\User::where('email', $user->email)->where('is_email_verified', 1)->exists();
                                    @endphp
                                    @if($is_verified)
                                    <form method="POST" action="{{route('accept.approval')}}" class="form1">
                                        @csrf
                                        <input type="hidden" name="userid" value="{{$user->id}}">
                                        <button class="btn btn-success" style="font-size:10px;margin-bottom:-20px;margin-left:15px;" onclick="return checkavl()">Accept</button>
                                    </form>
                                    <form method="POST" action="{{route('reject.approval')}}" class="form1">
                                        @csrf
                                        <input type="hidden" name="userid" value="{{$user->id}}">
                                        <button class="btn btn-danger" style="margin-left:90px;margin-bottom:-27px;margin-top:-55px;font-size:10px">Reject</button>
                                    </form>
                                    @else
                                    <form method="POST" action="{{route('reject.approval')}}" class="form1">
                                        @csrf
                                        <input type="hidden" name="userid" value="{{$user->id}}">
                                        <button class="btn btn-danger" style="font-size:10px;margin-left:50px;">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h2 style="font-size:13px">No pending requests</h2>
                    @endif
                </div>
            </div>
        </div>
        <!-- END Full Table -->
    </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->


<!--</div>-->
<!-- END Page Container -->
<script type="text/javascript">
    function checkavl() {
        var total = parseInt(document.getElementById('size').innerHTML, 10);
        var cur = parseInt(document.getElementById('curr').innerHTML, 10);
        if (cur >= total) {
            alert('You Cannot Approve Users As Company Reached The Specified Size Limit.To Approve User Please Wait for Company Size to Be Expand');
            return false;
        }
        return true;
    }
</script>


</body>

</html>
@endsection
