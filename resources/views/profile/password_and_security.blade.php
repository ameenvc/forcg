@extends($layout)
@section('content')
    <style>
        .authenticator-app-description {
            font-size: 12px;
        }
        .btn-setup {
            font-size: 12px;
        }
        .txt14px {
            font-size: 14px;
        }
        .badge-orange {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            font-size: 9px;
            font-weight: 600;
            color: #CC8800;
            background-color: #FFEBCC;
            border: 1px solid #D8BFA8;
            border-radius: 16px;
        }
        .badge-purple {
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            color: #6A1B9A;
            background-color: #F0E1F7;
            border-radius: 16px;
            border: 1px solid #B98AD4;
        }
        .badge-green {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            font-size: 9px;
            font-weight: 600;
            color: #176044;
            background-color: #e0edcf;
            border: 1px solid #A8D5B5;
            border-radius: 16px;
        }
        .green-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #28a745;
            border-radius: 50%;
        }
        .orange-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #CC8800;
            border-radius: 50%;
        }

        .swal-actions {
            width: 100%;
            justify-content: center;
        }

        .swal-actions .swal2-cancel {
            order: 1;
        }

        .swal-actions .swal2-confirm {
            order: 2;
        }
        #left {
    flex: 1;
    min-width: 0;
}

#left p {
    max-width: 500px;
    margin-bottom: 0;
}

#right {
    flex: 0 0 auto;
    white-space: nowrap;
}
    </style>
    <!-- Hero -->
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0">
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">
                        <li class="breadcrumb-item">Profile</li>
                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="">Password & Security</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- END Hero -->
    <!-- Page Content -->
    <div class="content">
        <!-- Basic -->
        <div class="block block-rounded">
            <div class="block-header">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <a class="close" data-dismiss="alert">×</a>
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <a class="close" data-dismiss="alert">×</a>
                        {{ session('error') }}
                    </div>
                @endif
                @include('flashMessage.flash_message')
                <h3 class="block-title"></h3>
            </div>
            <div class="block-header">
                <h5>Update Password</h5>
            </div>
            <div class="block-content block-content-full">
                <form action="{{ route('profile.changePassword') }}" method="POST" enctype="multipart/form-data" onsubmit="return validation()"  id="dropdownForm">
                    {{csrf_field()}}
                    <div class="d-flex justify-content-center" >
                        <div class="d-block col-lg-8">
                            <div class="form-group row {{ $errors->has('current-password') ? ' has-error' : '' }}">
                                <label for="new-password" class="col-md-4 control-label" style="font-size:13px">Current Password<span class="text-danger">*</span></label>
                                <div class="col-md-6">
                                    <input id="current-password" type="password" style="font-size:13px" class="form-control" name="current-password" required>

                                    @if ($errors->has('current-password'))

                                            <strong style="color:red">{{ $errors->first('current-password') }}</strong>

                                    @endif
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('new-password') ? ' has-error' : '' }}">
                                <label for="new-password"  style="font-size:13px" class="col-md-4 control-label">New Password<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="new-password" style="font-size:13px" type="password" class="form-control" name="new-password" required>

                                    @if ($errors->has('new-password'))

                                            <strong style="color:red">{{ $errors->first('new-password') }}</strong>

                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="new-password-confirm" style="font-size:13px" class="col-md-4 control-label">Confirm New Password<span class="text-danger">*</span></label>

                                <div class="col-md-6">
                                    <input id="new-password-confirm" type="password" style="font-size:13px" class="form-control" name="new-password_confirmation" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-10 text-right">
                                    <button type="submit" class="btn btn-primary" style="font-size:13px">
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            @if ($mfa)
                <hr>
                <div class="block-header">
                    <h5>Security & Sign-In</h5>
                </div>
                <div class="d-flex justify-content-center" >
                    <div class="d-block col-lg-8">
                        <div class="row justify-content-between align-items-center">
                            <div id="left" class="col-auto">
                                <strong>Multi Factor Authentication</strong>
                                <div class="m-0">
                                    <p style="font-size: 0.75rem">MFA adds an extra layer of protection when signing in by requiring a one-time verification code in addition to your password</p>
                                </div>
                            </div>
                            <div id="right" class="">
                                <div class="col-auto d-flex ">
                                    <label>
                                        @if ($mfa->meta_value)
                                            <span class="badge-purple"><i class="fas fa-lock"></i>&nbsp;<span style="margin: 0">Enforced by Admin</span></span>
                                        @endif
                                        &nbsp;
                                        @if ($mfa->meta_value || auth()->user()->enable_2fa)
                                            <span class="badge-green"><span class="green-dot"></span>&nbsp;<span style="margin: 0;">Enabled</span></span>
                                        @else
                                            <span class="badge-orange"><span class="orange-dot"></span>&nbsp;<span style="margin: 0">Disabled</span></span>
                                        @endif
                                    </label>
                                    <div class="col-auto">
                                        <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input
                                            id="enable_2fa"
                                            type="checkbox"
                                            name="enable_2fa"
                                            value="{{true}}"
                                            class="custom-control-input mandatory-field mandatory-toggle"
                                            {{auth()->user()->enable_2fa || $mfa->meta_value ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="enable_2fa"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <hr>
                        <div class="row justify-content-between align-items-center">
                            <div class="col">
                                <div>
                                    <strong>Authenticator App</strong>&nbsp;@if (auth()->user()->google2fa_secret)<span class="app-status">Configured & {{($mfa->meta_value || auth()->user()->enable_2fa) ? 'Active' : 'Inactive'}}</span>@endif
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
            @endif

        </div>
        <!-- END Basic -->
    </div>
    <!-- END Page Content -->
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
    $('#enable_2fa').on('click', function(event) {
        let setupped = "{{auth()->user()->google2fa_secret}}"
        if ({{$mfa->meta_value}}) {
            Swal.fire({
                html: `
                    <div class="swal-message">
                        <p class="swal-confirm-text">
                            <strong>MFA Enforced by Administrator</strong>
                        </p>
                        <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 2rem">
                            <div style="color:  #af1e1e; height: 5rem; width: 5rem; padding: 0.5 rem; border-radius: 8px; background-color: #f8d4d4; display: flex; justify-content: center; align-items: center;">
                                <svg style="width: 3rem; height: 3rem" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <p>
                            Multi-Factor Authentication is currently enforced organization-wide by your administrator. It cannot be disabled for individual user accounts.
                        </p>
                    </div>
                `,
                confirmButtonText: 'Understood',
                confirmButtonColor: '#5179d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.preventDefault();
                } else {
                    event.preventDefault();
                }
            });
            event.preventDefault();
        }else if (setupped == null) {
            Swal.fire({
                html: `
                    <div class="swal-message">
                        <p class="swal-confirm-text">
                            <strong>Set up Multi-Factor Authentication</strong>
                        </p>
                        <p style="font-size: 0.85rem">
                            Enhance your account security by linking an authenticator app. Complete the setup below to enable MFA.
                        </p>

                    </div>
                `,
                confirmButtonText: 'Setup',
                confirmButtonColor: '#5179d6',
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                customClass: {
                    actions: 'swal-actions'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $('.btn-setup').click();
                } else {
                    event.preventDefault();
                }
            });
            event.preventDefault();
        }
    });

    $('#enable_2fa').on('change', function() {
        let enable_2fa = $(this).is(':checked');

        if (enable_2fa) {
            Swal.fire({
                html: `
                        <div class="swal-message">
                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 2rem">
                                <div style="color:  #176044; height: 5rem; width: 5rem; padding: 0.5 rem; border-radius: 8px; background-color: #e0edcf; display: flex; justify-content: center; align-items: center;">
                                    <svg style="height: 3rem; width: 3rem" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>

                            <p class="swal-confirm-text">
                                Enabling Multi-Factor Authentication adds two-step verification to your account. You will be required to enter a one-time verification code each time you sign-in.
                            </p>
                        </div>
                    `,
                showCancelButton: true,
                confirmButtonText: 'Confirm & Enable',
                confirmButtonColor: '#176044',
                cancelButtonText: 'Cancel',
                customClass: {
                    actions: 'swal-actions'
                }
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
                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 2rem">
                                <div style="color:  #cc8800; height: 5rem; width: 5rem; padding: 0.5 rem; border-radius: 8px; background-color: #ffebcc; display: flex; justify-content: center; align-items: center;">
                                    <svg style="height: 3rem; width: 3rem" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="swal-confirm-text">
                                Are you sure you want to disable Multi-Factor Authentication? Two-factor verification will be removed from your sign-in process, which reduces account security.
                            </p>
                        </div>
                    `,
                showCancelButton: true,
                confirmButtonText: 'Confirm & Disable',
                confirmButtonColor: '#cc8800',
                cancelButtonText: 'Cancel',
                customClass: {
                    actions: 'swal-actions'
                }
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
</script>
@endsection

