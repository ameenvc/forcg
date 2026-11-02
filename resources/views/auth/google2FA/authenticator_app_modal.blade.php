<style>
    :root {
        --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;

        --bg-app: #f8fafc;
        --bg-card: #ffffff;
        --bg-subtle: #f1f5f9;
        --bg-inset: #e2e8f0;

        --text-main: #0f172a;
        --text-muted: #64748b;
        --text-subtle: #94a3b8;

        --border-color: #e2e8f0;
        --border-hover: #cbd5e1;

        --brand-black: #0f172a;
        --brand-hover: #1e293b;

        --primary-blue: #2563eb;
        --primary-blue-bg: #eff6ff;
        --primary-blue-border: #bfdbfe;

        --emerald: #059669;
        --emerald-bg: #ecfdf5;
        --emerald-border: #a7f3d0;

        --amber: #d97706;
        --amber-bg: #fffbeb;
        --amber-border: #fde68a;

        --rose: #dc2626;
        --rose-bg: #fef2f2;
        --rose-border: #fecaca;

        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

        --radius-sm: 6px;
        --radius-md: 10px;
        --radius-lg: 16px;
        --radius-full: 9999px;

        --transition: all 0.2s ease-in-out;
    }

        /* Wizard Steps Indicator */
    .wizard-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.85rem;
    }

    .wizard-step-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-subtle);
    }

    .wizard-step-item.active {
        color: var(--brand-black);
        font-weight: 800;
    }

    .wizard-step-item.completed .step-num {
        background-color: var(--primary-blue-bg);
        color: var(--primary-blue);
        border: 1px solid var(--primary-blue-border);
    }
    .wizard-step-item.completed {
        color: var(--primary-blue);
    }

    .step-num {
        width: 22px;
        height: 22px;
        border-radius: var(--radius-full);
        background-color: var(--bg-subtle);
        color: var(--text-subtle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .wizard-step-item.active .step-num {
        background-color: var(--primary-blue);
        color: #ffffff;
    }

    .btn12px {
        font-size: 12px;
    }
    .form-input {
        width: 100%;
        padding: 0.6rem 0.85rem;
        border-radius: var(--radius-md);
        border: 1px solid var(--border-color);
        background-color: #ffffff;
        color: var(--text-main);
        font-size: 0.8rem;
        transition: var(--transition);
        outline: none;
    }
    .app-status {
        padding: 5px;
        border-radius: 4px;
        font-size: 10px;
        background: #e0edcf;
        color: #176044;
    }
</style>

<div class="modal fade" id="modal-google-auth" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Google Authenticator Setup</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <!-- Step Progress Bar -->
            <div style="padding: 0.75rem 1.25rem; background-color: var(--bg-card); border-bottom: 1px solid var(--border-color);">
                <div class="wizard-steps">
                    <div id="wizard-step-1-indicator" class="wizard-step-item active">
                        <span class="step-num">1</span> <span>Verify Email</span>
                    </div>
                    <div id="wizard-step-2-indicator" class="wizard-step-item">
                        <span class="step-num">2</span> <span>Get App</span>
                    </div>
                    <div id="wizard-step-3-indicator" class="wizard-step-item">
                        <span class="step-num">3</span> <span>Scan QR</span>
                    </div>
                    <div id="wizard-step-4-indicator" class="wizard-step-item">
                        <span class="step-num">4</span> <span>Enter & Verify</span>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <!-- STEP 1: Verify Email -->
                <div id="wizard-step-1" class="wizard-step-content" style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="text-align: center;">
                        <div style="font-size: 2rem;">📧</div>
                        <h3 style="font-size: 1rem; font-weight: 800;">Step 1: Verify Email Authorization</h3>
                        <p id="mail-to-be-sent-message" style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                            To verify its you, We have to send 6-digit OTP code, <button onclick="sendOtp()" type="button" class="btn btn-link p-0" style="font-size: 0.78rem; font-weight:bold">click here to send OTP</button>
                        </p>
                        <p id="mail-sending-message" style="display:none; font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                            To verify its you, We are sending a 6-digit OTP code to <strong id="email-otp-target-email">{{auth()->user()->email}}</strong>, Please wait..
                        </p>
                        <p id="mail-sending-error-message" style="display:none; font-size: 0.78rem; color: var(--rose); margin-top: 0.2rem;">
                            Mail didn't send, There is an internal server error, please refresh the page or try after sometime
                        </p>
                        <p id="mail-sent-message" style="display:none; font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                            We sent a 6-digit confirmation OTP code to <strong id="email-otp-target-email">{{auth()->user()->email}}</strong>
                        </p>
                    </div>

                    <!-- Email OTP Input Digit Box -->
                    <div class="d-flex justify-content-center" style="margin-bottom: 0px;">
                        <div id="email_otp_container" class="form-group" style="display: none;">
                            <input
                                disabled
                                class="form-input"
                                style="font-size: 16px; font-weight:bold; text-align:center"
                                id="email_otp"
                                name="email_otp"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                placeholder="Enter OTP"
                                maxlength="6"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                            <div id="email-otp-error-msg" style="display: none; color: var(--rose); border-radius: var(--radius-md); font-size: 0.75rem; text-align: center; font-weight: 600;">
                                Invalid email OTP code. Please check your inbox and try again.
                            </div>
                        </div>
                    </div>



                    <div style="display: flex; justify-content: space-between; pt: 0.5rem;">
                        <button id="btn-resend-otp" style="display: none; font-size: 12px;" disabled class="btn btn-primary">Resend OTP <span id="countdown">in 60s</span></button>
                        <button id="btn-step1-verify"  style="display: none; font-size: 12px;" class="btn btn-dark">Verify Email OTP &rarr;</button>
                    </div>
                </div>

                <!-- STEP 2: Get App -->
                <div id="wizard-step-2" class="wizard-step-content" style="display: none; flex-direction: column; gap: 1rem; text-align: center;">
                    <div style="font-size: 2.5rem; margin-top: 0.5rem;">📱</div>
                    <h3 style="font-size: 1.1rem; font-weight: 800;">Step 2: Download Google Authenticator</h3>
                    <p style="font-size: 0.8rem; color: var(--text-muted); max-width: 400px; margin: 0 auto; line-height: 1.5;">
                    Download the official Google Authenticator app on your mobile device from the iOS App Store or Google Play Store.
                    </p>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; margin: 0.5rem 0;">
                        <a href="https://apps.apple.com/app/google-authenticator/id388497605" target="_blank" class="btn btn-secondary" style="padding: 0.75rem;">
                         iOS App Store ↗
                        </a>
                        <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" target="_blank" class="btn btn-secondary" style="padding: 0.75rem;">
                        ▶ Google Play ↗
                        </a>
                    </div>

                    <div style="background-color: var(--primary-blue-bg); border: 1px solid var(--primary-blue-border); padding: 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; color: var(--primary-blue); text-align: left;">
                    💡 Already have a TOTP app (Google Authenticator, Microsoft Authenticator, 1Password)? Click <strong>Next</strong> to scan the QR code.
                    </div>

                    <div style="display: flex; justify-content: flex-end; pt: 0.5rem;">
                        <button id="btn-step2-next" class="btn btn-dark btn12px">Next: Scan QR Code &rarr;</button>
                    </div>
                </div>

                <!-- STEP 3: Scan QR Code -->
                <div id="wizard-step-3" class="wizard-step-content" style="display: none; flex-direction: column; gap: 1rem;">
                    <div style="text-align: center;">
                        <h3 style="font-size: 1rem; font-weight: 800;">Step 3: Scan QR Code</h3>
                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                            Open Google Authenticator, tap the <strong>+</strong> button, and select <strong>Scan a QR code</strong>.
                        </p>
                    </div>

                    <div style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 0.75rem;">
                        <div id="qr-code-img-container" style="background-color: #ffffff; padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">

                        </div>
                        <span style="font-size: 0.7rem; color: var(--text-muted);">Account: <strong id="qr-account-label">{{auth()->user()->email}}</strong></span>
                    </div>

                    <!-- Secret Key Fallback Box -->
                    <div style="background-color: var(--bg-subtle); border: 1px solid var(--border-color); padding: 0.75rem; border-radius: var(--radius-md); display: flex; flex-direction: column; gap: 0.35rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.72rem;">
                            <span style="font-weight: 700; color: var(--text-main);">Can't scan? Enter Secret Key manually:</span>
                            <button id="btn-copy-secret" class="btn btn-dark btn-sm" style="padding: 0.2rem 0.5rem;">Copy Key</button>
                        </div>
                        <div id="secret-key-display" style="font-family: var(--font-mono); font-weight: 800; font-size: 0.9rem; text-align: center; letter-spacing: 0.1em; background-color: #ffffff; padding: 0.4rem; border-radius: var(--radius-sm); border: 1px solid var(--border-color);">

                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; pt: 0.5rem;">
                        <button id="btn-step3-back" class="btn btn-dark btn12px">&larr; Back</button>
                        <button id="btn-step3-next" class="btn btn-dark btn12px">Next: Enter 6-Digit Code &rarr;</button>
                    </div>
                </div>

                <!-- STEP 4: Enter 6-Digit Code -->
                <div id="wizard-step-4" class="wizard-step-content" style="display: none; flex-direction: column; gap: 1rem;">
                    <div style="text-align: center;">
                        <h3 style="font-size: 1rem; font-weight: 800;">Step 4: Enter Verification Code</h3>
                        <p style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                        Enter the 6-digit passcode generated by Google Authenticator.
                        </p>
                    </div>

                    <div class="d-flex justify-content-center">
                        <div class="form-group">
                            <input
                                style="font-size: 16px; font-weight:bold; text-align:center"
                                name="otp"
                                id="otp"
                                class="form-input"
                                value=""
                                inputmode="numeric"
                                pattern="[0-9]*"
                                placeholder="Enter TOTP"
                                maxlength="6"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            />
                        </div>
                    </div>

                    <div id="otp-error-msg" style="display: none; background-color: var(--rose-bg); border: 1px solid var(--rose-border); color: var(--rose); padding: 0.5rem 0.75rem; border-radius: var(--radius-md); font-size: 0.75rem; text-align: center; font-weight: 600;">
                        Invalid verification code. Please try again.
                    </div>

                    <div style="display: flex; justify-content: space-between; pt: 0.5rem;">
                        <button id="btn-step4-back" class="btn btn-dark btn12px">&larr; Back</button>
                        <button id="btn-step4-verify" class="btn btn-primary btn12px">Verify Code & Activate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#modal-google-auth').on('hidden.bs.modal', function () {
            $("#mail-to-be-sent-message").show();
            $("#mail-sending-message").hide();
            $("#mail-sending-error-message").hide();
            $("#email-otp-error-msg").hide();
            $("#mail-sent-message").hide();
            $("#email_otp_container").hide();
            $("#email_otp").val('');
            $("#btn-resend-otp").hide();
            $("#btn-step1-verify").text("Verify Email OTP");
            $("#btn-step1-verify").hide();
            goToWizardStep(1);
        });
    });
</script>
<script>

    function sendOtp() {
        $("#mail-to-be-sent-message").hide();
        $("#mail-sending-message").show();
        sendOtpToEmail();
    }

    $('#btn-resend-otp').on('click', function () {
        sendOtpToEmail();
    });

    $('#btn-step1-verify').on('click', function () {
        verifyEmailOtp();
    });

    $('#btn-step2-next').on('click', function () {
        goToWizardStep(3);
        get2faSetup();
    });

    $('#btn-step3-back').on('click', function () {
        goToWizardStep(2);
    });

    $('#btn-step3-next').on('click', function () {
        goToWizardStep(4);
    });

    $('#btn-step4-back').on('click', function () {
        goToWizardStep(3);
    });

    $('#btn-step4-verify').on('click', function () {
        verifyTOTP();
    });

    $('#otp').on('input', function () {
        $("#otp-error-msg").hide();
    });

    $("#btn-copy-secret").on('click', function () {
        copySecret();
    })

    function copySecret(params) {
        const secret = $("#secret-key-display").text().trim();
        navigator.clipboard.writeText(secret)
        .then(function () {
            alert('Copied!');
        });
    }

    function sendOtpToEmail() {
        $.ajax({
            url: "{{route('2fa.sendOtpToMail')}}",
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status == 1) {
                    $("#mail-sending-message").hide();
                    $("#mail-sending-error-message").hide();
                    $("#mail-sent-message").show();
                    $("#email_otp_container").show();
                    $("#btn-resend-otp").show();
                    $("#btn-step1-verify").show();
                    $('#email_otp').prop('disabled', false);
                    startOtpTimer()
                } else {
                    $("#mail-sending-message").hide();
                    $("#mail-sending-error-message").show();
                }

            },
            error: function(error) {
                console.log(error)
                $("#mail-sending-message").hide();
                $("#mail-sending-error-message").show();
            },
            complete: function() {

            }
        });
    }

    let timer;
    let timeLeft = 60;

    function startOtpTimer() {

        // Reset timer
        clearInterval(timer);
        timeLeft = 60;

        // Disable resend button
        $('#btn-resend-otp').prop('disabled', true);

        // Show initial value
        $('#countdown').text('in '+timeLeft+'s');

        timer = setInterval(function () {

            timeLeft--;

            $('#countdown').text('in '+timeLeft+'s');

            if (timeLeft <= 0) {
                clearInterval(timer);
                $('#countdown').text('');
                $('#btn-resend-otp').prop('disabled', false);
            }

        }, 1000);
    }

    function verifyEmailOtp() {
        $("#btn-step1-verify").text("Verifying...");
        let mail_otp = $('#email_otp').val();

        $.ajax({
            url: "{{route('2fa.verifyMailOtp')}}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                mail_otp: mail_otp
            },
            success: function(response) {

                console.log(response);
                if (!response.status) {
                    $("#email-otp-error-msg").show();
                    $("#btn-step1-verify").text("Verify Email OTP");
                } else if(response.status) {
                    goToWizardStep(2);
                }
            },
            error: function() {
                console.log(error)
            },
            complete: function() {

            }
        });

    }

    function goToWizardStep(step) {
        wizardCurrentStep = step;
        [1, 2, 3, 4].forEach(s => {
            const $content = $(`#wizard-step-${s}`);
            const $indicator = $(`#wizard-step-${s}-indicator`);

            if (s === step) {
                $content.css('display', 'flex');
                $indicator.attr('class', 'wizard-step-item active');
            } else if (s < step) {
                $content.hide();
                $indicator.attr('class', 'wizard-step-item completed');
            } else {
                $content.hide();
                $indicator.attr('class', 'wizard-step-item');
            }
        });

        if (step === 3) {
            $('#otp-0').focus();
        }
    }
    function get2faSetup() {
        $.ajax({
            url: "{{route('2fa.setup')}}",
            type: 'get',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response);
                $("#secret-key-display").text(response.secret);
                $("#qr-code-img-container").html(response.QR_Image);
            },
            error: function() {
                console.log(error)
            },
            complete: function() {

            }
        });
    }

    function verifyTOTP() {
        let otp = $('#otp').val();
        $.ajax({
            url: "{{route('2fa.setup.post')}}",
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
                    $('#modal-google-auth').modal('hide');
                    window.location.reload();
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
