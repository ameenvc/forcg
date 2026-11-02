<style>
    .avatar-thumb {
        border: 1px solid #80808059;
        background: #eeeeee36;
        margin-top: 0.5rem;
        height: 8rem;
    }
    .avatar-thumb img {
        padding: 1rem;
        object-fit: contain;
        width: 100%;
        height: 100%;
    }
    .authenticator-app-description {
        font-size: 12px;
    }
    .btn-setup {
        font-size: 12px;
    }
    .txt14px {
        font-size: 14px;
    }
</style>
<main id="main-container">
    <div class="content" style="width:88%;margin-left:70px;margin-top:-75px">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit Profile</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb" style="margin-left:70px">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="#" onclick="history.back(); return false;">Back</a>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="block-content block-content-full" style="width:90%;margin-left:100px">
                @if(Session::has('msg'))
                    <div class="alert alert-success">
                        <a href="javascript:void(0)" class="close" data-dismiss="alert">×</a>
                        {!!Session::get('msg')!!}
                    </div>
                @endif
                @include('flashMessage.flash_message')

                @if(count($errors) >0 )
                    @foreach($errors->all() as $error)
                    <div class='alert alert-warning'>
                    <a class="close" data-dismiss="alert">×</a>
                        {{ $error }}
                    </div>
                    @endforeach
                @endif
                <div class="row">
                    <div class="col-lg-9">
                        <form class="mb-5" method="POST" action="{{url('profile/update')}}" onsubmit="return validation()" enctype="multipart/form-data" >
                            {{csrf_field()}}
                            <div class="col-lg-10 offset-1">
                               <div class="form-group row">
                                   <label class="col-sm-4 col-form-label" style="font-size: 13px;" for="type">Name</label>
                                   <div class="col-sm-8">
                                    <input type="text"name="name" value= "{{$users[0]->name}}" style="font-size: 13px;" class="form-control" readonly >
                                   </div>
                               </div>

                               <div class="form-group row">
                                   <label style="font-size: 13px;" class="col-sm-4 col-form-label" for="title" >Email</label>
                                   <div class="col-sm-8">
                                   <input type="text" class="form-control" style="font-size: 13px;" name="email" value= "{{$users[0]->email}}" readonly>
                                   </div>
                               </div>

                               <div class="form-group row">
                                   <label class="col-sm-4 col-form-label" for="issue" style="font-size: 13px;" >Employee ID</label>
                                   <div class="col-sm-8">
                                   <input type="text" class="form-control"  style="font-size: 13px;"  id="dept_id" name="empid" value= "{{$users[0]->employeeid}}" readonly>
                                   </div>
                               </div>
                               <div class="form-group row">
                                   <label class="col-sm-4 col-form-label" for="profile_picture"
                                          style="font-size: 13px;">Profile picture</label>
                                   <div class="col-sm-8">
                                       <input type="file" class="form-control"
                                              accept="image/*"
                                              style="font-size: 13px;"
                                              id="profile_picture"
                                              name="profile_pic">
                                   </div>
                               </div>
                               <input type="hidden" name="id" value= "{{$users[0]->employeeid}}" >
                               <div class="form-group" style="display: flex; justify-content: end">
                                   <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                               </div>
                            </div>
                       </form>
                    </div>
                    <div class="col-lg-2">
                        <div class="avatar-thumb">
                            @if ($users[0]->profile_picture && file_exists(public_path('/storage/profile/'. $users[0]->profile_picture)))
                                <img id="imgPreview"
                                     src="{{ asset("storage/profile/". $users[0]->profile_picture) }}"
                                     alt="{{ $users[0]->name }}">
                            @else
                                <img id="imgPreview"
                                     src="{{ asset('/images/avatar10.jpg') }}"
                                     alt="{{ $users[0]->name }}">
                            @endif
                        </div>
                        <div class="col-lg-12 text-center">
                            <a href="{{ route('profile.removeAvatar')}}" class="text-danger" style="font-size:14px;">Delete Image</a>
                        </div>
                    </div>
               </div>
           </div>
           {{-- @if ($mfa)
                <hr>
                <div class="block-header">
                    <h5>Security & Sign-In</h1>
                </div>
                <div class="d-flex justify-content-center" >
                    <div class="d-block col-lg-8">
                        <div class="row justify-content-between ">
                            <div class="col-auto"><strong>Two Factor Authentication</strong></div>
                            <div class="col-auto">
                                <div class="custom-control custom-switch custom-control-success mb-1">
                                <input
                                    id="enable_2fa"
                                    type="checkbox"
                                    name="enable_2fa"
                                    value="{{true}}"
                                   {{auth()->user()->google2fa_secret ? "" :  "disabled"}}
                                    class="custom-control-input mandatory-field mandatory-toggle"
                                    {{auth()->user()->enable_2fa ? 'checked' : ''}}>
                                    <label class="custom-control-label" for="enable_2fa"></label>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <div>
                                    <strong>Authenticator App</strong>&nbsp;@if (auth()->user()->google2fa_secret)<span class="app-status">Configured & {{auth()->user()->enable_2fa ? 'Active' : 'Inactive'}}</span>@endif
                                    <p style="margin-top: 0.5rem;" class="authenticator-app-description">Use the official Google/Microsoft Authenticator app on iOS or Android to generate time-based one-time passcodes (TOTP).</p>
                                </div>
                            </div>
                            <div class="col-auto">
                                @if (auth()->user()->google2fa_secret == null)
                                    <button class="btn btn-dark btn-setup"  data-toggle="modal" data-target="#modal-google-auth">Setup</button>
                                @else
                                    <button class="btn btn-dark btn-setup"  data-toggle="modal" data-target="#modal-google-auth">Reconfigure</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div style="height: 50px;"></div>
            @endif --}}
       </div>
   </div>
    <!-- END Page Content -->
</main>
<!-- END Main Container -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@include('auth.google2FA.authenticator_app_modal')

<script>
    $(document).ready(function () {
        let successMessage = sessionStorage.getItem('success_message');
        if (successMessage) {

        // Display your message
            $("#flash-content-message").text(successMessage);
            if (successMessage == 'Enabled Two Factor Authentication') {
                $("#flash-message").addClass('alert-success');
            } else {
                $("#flash-message").addClass('alert-warning');
            }
            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            $("#flash-message").show();
            // Remove it after displaying
            sessionStorage.removeItem('success_message');
        }
    });
</script>
<script>
    $('#enable_2fa').on('change', function() {
        let enable_2fa = $(this).is(':checked');

        if (enable_2fa) {
            Swal.fire({
                html: `
                        <div class="swal-message">
                            <p class="swal-confirm-text">
                                <strong>Are you sure you want to enable MFA?</strong>
                            </p>
                            <p>
                                MFA will be enabled <strong>for your account</strong>, and you will be required to complete MFA when logging in.
                            </p>

                            <p class="swal-confirm-text">
                                <strong>Do you want to proceed?</strong>
                            </p>
                        </div>
                    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the action
                    enable2fa(enable_2fa)
                } else {
                    $('#enable_2fa').prop('checked', false);
                }
            });
        } else {
            Swal.fire({
                html: `
                        <div class="swal-message">
                            <p class="swal-confirm-text">
                                <strong>Are you sure you want to disable MFA?</strong>
                            </p>
                            <p>
                                MFA will be disabled <strong>for your account</strong>, and MFA will no longer be required when you log in.
                            </p>

                            <p class="swal-confirm-text">
                                <strong>Do you want to proceed?</strong>
                            </p>
                        </div>
                    `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform the action
                    enable2fa(enable_2fa)
                } else {
                    $('#enable_2fa').prop('checked', true);
                }
            });
        }
    });

    function enable2fa(enable_2fa) {
        $.ajax({
            url: "{{route('2fa.userEnableDisable2FA.post')}}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                enable_2fa
            },
            success: function(response) {
                sessionStorage.setItem('success_message', response.message);
                console.log(response.message)
                window.location.reload();
            },
            error: function() {
                console.log(error)
            },
            complete: function() {

            }
        });
    }

function ddlselect()
{
    var d=document.getElementById("dept");
    var displaytext=d.options[d.selectedIndex].text;
    document.getElementById("dept_id").value=displaytext;

}

</script>
        <script>
function validation() {


    if(document.getElementById('dept').value == 0){
            alert('Please select a department');
            document.getElementById('dept').focus();
            return false;
         }



  // always return false to prevent form from submission.
}
</script>
