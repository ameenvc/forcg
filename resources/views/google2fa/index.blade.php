@extends('layouts.layout2fa')
@section('content')
    <style>
        :root {
            --rose: #dc2626;
            --rose-bg: #fef2f2;
            --rose-border: #fecaca;
        }
        #success-message {
            background: #e0edcf;
            color: #176044;
            padding: 5px 16px;
            border-width: 1px;
            font-size: 20px;
            width: 100%;
        }
    </style>
    @include('flashMessage.flash_message')

    <div style="position: absolute; top: 70px; left: 10px; width: 100%;">
        <div class="alert alert-success" style="display: none; font-size:14px; position: relative; width: 98%">
            <a class="close" data-dismiss="alert" style="position: absolute;right: 10px;color:#0a0a0a;cursor: pointer;">×</a>
            2Fa Setup Complete
        </div>
    </div>
    <div class="flex-grow-1 d-flex justify-content-center align-items-center">
            <div class="card shadow-sm" style="max-width: 400px; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center mb-3">Two-Factor Authentication</h4>

                <p class="text-muted text-center mb-4">
                    Enter the 6-digit verification code from your authenticator app.
                </p>
            <form method="POST" action="{{ route('2fa.verify') }}">
                {{ csrf_field() }}
                @if (request()->path() != '2fa/verify')
                    {{ session(['2fa_redirect_url' => request()->path()]) }}
                @endif
                <div class="mb-3">
                    <label for="otp" class="form-label">Verification Code</label>
                    <div style="margin-bottom: 10px">
                        <input
                        type="text"
                        class="form-control text-center"
                        id="otp"
                        name="one_time_password"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="6"
                        placeholder="123456"
                        autocomplete="one-time-code"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        autofocus>
                    </div>
                        @if ($errors->any())
                            <div class="alert alert-danger mt-1" id="error-message" style="font-size: 12px;">
                                <ul style="margin: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <script>
                                setTimeout(function () {
                                    $('#error-message').fadeOut('slow');
                                }, 4000);
                            </script>
                        @endif
                    <div id="otp-error-msg" style="display: none; background-color: var(--rose-bg); border: 1px solid var(--rose-border); color: var(--rose); padding: 0.5rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; text-align: center; font-weight: 600;">
                        Invalid verification code. Please try again.
                    </div>
                </div>

                <button id="verify" type="submit" class="btn btn-primary w-100">
                    Verify
                </button>
                <div id="reset-button" style="display: none">
                    <div class="d-flex row justify-content-end mt-3" ><a style="font-size: 12px; font-weight:bold;" href="{{route('2fa.setupPage')}}">Click to reset & set up again</a></div>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
@section('js_after')
    <script>
        $(document).ready(function() {
            let successMessage = sessionStorage.getItem('success_message');
            let messageConfig = JSON.parse(sessionStorage.getItem('message_config'));
            if (successMessage) {
                // Display your message
                $("#flash-content-message").text(successMessage);
                $("#flash-message").addClass(messageConfig? messageConfig.class : 'alert-success');
                $("#flash-message").show();
                // Remove it after displaying
                sessionStorage.removeItem('success_message');
            } else {
                $("#reset-button").show();
            }
        });
    </script>
    <script>
        // $('#verify').on('click', function() {
        //    verifyTOTP();
        // })

        $('#otp').on('input', function () {
            $("#otp-error-msg").hide();
        });

        function verifyTOTP() {
            let otp = $('#otp').val();
            $.ajax({
                url: "{{route('2fa.login.post')}}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    one_time_password: otp
                },
                success: function(response) {
                    console.log(response);

                    if (!response.status) {
                        $("#otp-error-msg").show();
                    } else if(response.status) {
                        window.location.href = "{{ route('user') }}";
                    }
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
