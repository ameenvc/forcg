@extends('admin.datatable')
@section('content')
    <style>
        #mfa-status-message {
            color: #888;
            font-size: 12px;
        }
        .green-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #176044;
            border-radius: 50%;
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
        .orange-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background-color: #CC8800;
            border-radius: 50%;
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

        .status-enabled {
            background: #e0edcf;
            color: #176044;
            border-radius: 16px;
            padding: 5px 8px;
            font-size: 10px;
        }
        .status-disabled {
            color: #664D03;
            background-color: #FFF3CD;
            border-radius: 16px;
            padding: 5px 8px;
            font-size: 10px;
        }
        .swal-html-container {
            font-size: 15px !important;
            color: #555 !important;
            line-height: 1.6;
        }

        .swal-message p {
            margin-bottom: 12px;
        }

        .swal-confirm-text {
            margin-top: 20px;
            color: #333;
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
    </style>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-content block-content-full" style="width:90%;">
                <div class="col-lg-10 col-md-12" style="padding-left: 0">
                    <div class="form-group row justify-content-start">
                        <div class="col-lg-12 d-flex align-items-center justify-content-between">
                            <div>
                                <label style="font-size: 13px;" for="company_name">
                                    Multi Factor Authentication
                                </label>
                            </div>

                            <div class="d-flex align-items-center">
                                <div id="mfa-status-message" class="ml-2 d-none"></div>&nbsp;&nbsp;
                                <label>
                                    @if ($mfa->meta_value)
                                        <span class="badge-green">
                                            <span class="green-dot"></span>&nbsp;
                                            <span style="margin: 0">Enabled</span>
                                        </span>
                                    @else
                                        <span class="badge-orange">
                                            <span class="orange-dot"></span>&nbsp;
                                            <span style="margin: 0">Disabled</span>
                                        </span>
                                    @endif
                                </label>
                                &nbsp;
                                <div class="d-flex align-items-center">
                                    <div class="custom-control custom-switch custom-control-success mb-1">
                                        <input
                                            id="mfa"
                                            type="checkbox"
                                            name="enable_mfa"
                                            value="{{$mfa->meta_value}}"
                                            class="custom-control-input mandatory-field mandatory-toggle"
                                            {{$mfa->meta_value ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="mfa"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                        <span style="font-size: 13px">MFA adds a second layer of security by requiring a one-time password in addition to user credentials during sign-in.</span>
                        {{-- <br><br>
                        <strong style="font-size: 13px">When MFA is enabled:</strong> <br>
                        <span style="font-size: 13px">You will be asked to enter a verification code generated by your authenticator app during login. This helps protect your account even if your password is compromised.</span><br><br>
                        <strong style="font-size: 13px">When MFA is disabled:</strong> <br>
                        <span style="font-size: 13px">You will no longer be required to enter an MFA verification code during login. Your account will rely only on your username/email and password for authentication.</span><br><br>
                        <span style="font-size: 13px"><strong>Important:</strong> Disabling MFA reduces the security of your account. We recommend keeping MFA enabled to provide better protection against unauthorized access.</span> --}}
                    </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            let successMessage = sessionStorage.getItem('success_message');
            if (successMessage) {
                // Display your message
                $("#flash-content-message").text(successMessage);
                if (successMessage == 'MFA Enabled') {
                    $("#flash-message").addClass('alert-success');
                } else {
                    $("#flash-message").addClass('alert-warning');
                }
                $("#flash-message").show();
                // Remove it after displaying
                sessionStorage.removeItem('success_message');
            }

            $('#mfa').on('click', function() {
                const isEnabled = $(this).is(':checked') ? 1 : 0;

                if (isEnabled) {
                    Swal.fire({
                        html: `
                                <div class="swal-message">
                                    <p class="swal-confirm-text">
                                        <strong>Enable Multi-Factor Authentication?</strong>
                                    </p>
                                    <div style="display: flex; justify-content: center; align-items: center; margin: 2rem">
                                        <div style="color:  #176044; height: 5rem; width: 5rem; padding: 0.5 rem; border-radius: 8px; background-color: #e0edcf; display: flex; justify-content: center; align-items: center;">
                                            <svg style="height: 3rem; width: 3rem" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p>
                                        This will enable Multi-Factor Authentication for <strong>all users</strong>, and all users will be required to configure MFA before they can log in.
                                    </p>

                                    <p class="swal-confirm-text">
                                        <strong>Do you want to proceed?</strong>
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
                            enableDisableMFA(isEnabled)
                        } else {
                            $('#mfa').prop('checked', false);
                        }
                    });
                } else {
                    Swal.fire({
                        html: `
                                <div class="swal-message">
                                    <p class="swal-confirm-text">
                                        <strong>Disable Multi-Factor Authentication?</strong>
                                    </p>
                                    <div style="display: flex; justify-content: center; align-items: center; margin: 2rem">
                                        <div style="color:  #cc8800; height: 5rem; width: 5rem; padding: 0.5 rem; border-radius: 8px; background-color: #ffebcc; display: flex; justify-content: center; align-items: center;">
                                            <svg style="height: 3rem; width: 3rem" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p>
                                        This will disable Multi-Factor Authentication for all users and will affect the MFA requirement during login.
                                    </p>

                                    <p class="swal-confirm-text">
                                        <strong>Do you want to proceed?</strong>
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
                            enableDisableMFA(isEnabled)
                        } else {
                            $('#mfa').prop('checked', true);
                        }
                    });
                }
            });
        });
    </script>
    <script>
        function enableDisableMFA(isEnabled) {
            const message = isEnabled ? 'Enabling...' : 'Disabling...';
            $('#mfa-status-message').html('<span>'+message+'</span>');
            $('#mfa-status-message').removeClass('d-none').addClass('d-inline-block');

            $.ajax({
                url: '{{route("admin.security.enableDisableMFA")}}',
                type: 'POST',
                data: {
                    mfa: isEnabled
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    var responseMessage = response.mfa == 1 ? 'Enabled' : 'Disabled';
                    $('#mfa-status-message').html('<span>'+responseMessage+'</span>');
                    sessionStorage.setItem('success_message', "MFA "+responseMessage);
                    setTimeout(function() {
                        $('#mfa-status-message').removeClass('d-inline-block').addClass('d-none');
                        $('#mfa-status-message').html('<span></span>');
                        window.location.reload();
                }, 1000);
                },
                error: function() {
                    console.log(error)
                    $('#mfa-status-message').removeClass('d-inline-block').addClass('d-none');
                    $('#mfa-status-message').html('<span></span>');
                },
                complete: function() {

                }
            });
        }
    </script>
@endsection
