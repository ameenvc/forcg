@extends('layouts.simple')
@section('content')
@php
$departments = App\Departments::where('is_active',true)->get();
@endphp
<style>
    .btn-register-success {
        color:#ffffff;
        /* background-color: #00b38f;
        border-color: #00b38f; */
        background-color: #24a88e;
        border-color: #24a88e;
        width:270px;
        border-radius: 7px;
    }
    

    .btn-register-success:hover {
        color: #ffffff;
        background-color: #24a88e;
        border-color: #24a88e;
        width:270px;
        border-radius: 7px;
    }

    .btn-register-success:focus, .btn-register-success.focus {
        color: #ffffff;
        background-color: #24a88e;
        border-color: #24a88e;
        width:270px;
        border-radius: 7px;
        box-shadow: 0 0 0 0.2rem rgba(142, 228, 195, 0.25);
    }
    .form-control.form-control-alt.is-invalid, .form-control.form-control-alt.is-invalid:focus {
        background-color: inherit;
        border-color: #e3342f;
    }
</style>
<div id="page-container">
    <main id="main-container">
        <div class="bg-primary-dark">
            <div class="row no-gutters bg-primary-dark-op">
                <div class="hero-static col-lg-4 d-none d-lg-flex flex-column justify-content-center">
                    {{-- <div class="align-items-center mt-4 ml-4" style="color:white"> --}}
                    {{-- </div> --}}
                    <div class="p-4 p-xl-5 flex-grow-1 d-flex align-items-center">

                        <div class="w-100" style="margin-top:-100px;">
                            <a class="link-fx font-w600 font-size-h2 text-white" href="/">
                                {{ config('app.name') }}
                            </a>
                            <p class="text-white-75 mr-xl-8 mt-2">
                                Welcomes to your amazing app. Feel free to signup and start managing your systems.
                            </p>
                        </div>
                    </div>
                    <div class="p-4 p-xl-5 d-xl-flex justify-content-between align-items-center font-size-sm">
                        <p class="font-w500 text-white-50 mb-0">
                            <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
                        </p>
                        <ul class="list list-inline mb-0 py-2">
                            <li class="list-inline-item">
                                <a class="text-white-75 font-w500" href="javascript:void(0)">Legal</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-white-75 font-w500" href="javascript:void(0)">Contact</a>
                            </li>
                            <li class="list-inline-item">
                                <a class="text-white-75 font-w500" href="javascript:void(0)">Terms</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="hero-static col-lg-8 d-flex flex-column align-items-center bg-white">
                    <div class="p-3 w-100 d-lg-none text-center">
                        <a class="link-fx font-w600 font-size-h3 text-dark" href="/">
                            {{ config('app.name') }}
                        </a>
                    </div>
                    <div class="p-4 w-100 flex-grow-1 d-flex align-items-center">
                        <div class="w-100">
                            <div class="text-center mb-5">
                                <p class="mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="0 0 24 24" fill="#8451d6">
                                        <path d="M12 6.76l1.379 4.246h4.465l-3.612 2.625 1.379 4.246-3.611-2.625-3.612 2.625 1.379-4.246-3.612-2.625h4.465l1.38-4.246zm0-6.472l-2.833 8.718h-9.167l7.416 5.389-2.833 8.718 7.417-5.388 7.416 5.388-2.833-8.718 7.417-5.389h-9.167l-2.833-8.718z" /></svg>
                                </p>
                                <h1 class="font-w700 mb-2">
                                    Create Account
                                </h1>
                                <h2 class="font-size-base text-muted">
                                    Get your access today in one easy step
                                </h2>
                            </div>
                            @if (session('verifyError'))
                            <div class="alert alert-danger" role="alert">
                                <a class="close" data-dismiss="alert">×</a>
                                {{ session('verifyError') }}
                            </div>
                            @endif
                            <div class="row no-gutters justify-content-center">
                                <div class="col-lg-10">
                                    <form class="js-validation-signup" action="{{route('register')}}" method="POST" onsubmit="return validation()" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <input type="text" class="form-control form-control-lg form-control-alt py-4 @error('name') is-invalid @enderror" id="signup-username" name="name" placeholder="Full Name" required>
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">
                                            <input type="email" class="form-control form-control-lg form-control-alt py-4 @error('email') is-invalid @enderror" id="signup-email" name="email" placeholder="Email" required>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div  style="display:inline-block;width:49%;">
                                             <input type="password" 
                                                title="Password must contain Minimum of 6 Characters (At least 1 Alphabet and 1 Digit)." 
                                                class="form-control form-control-lg form-control-alt py-4 @error('password') is-invalid @enderror" 
                                                id="signup-password" 
                                                name="password" 
                                                placeholder="Password" 
                                                required 
                                                pattern="^(?=.*[a-zA-Z])(?=.*\d).{6,}$" 
                                                onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">

                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div  style="display:inline-block;width:49%;">
                                            <input type="password" class="form-control form-control-lg form-control-alt py-4" id="signup-password-confirm" name="password_confirmation" placeholder="Confirm Password" required>
                                        </div>
                                        <div class="form-group">
                                          <label style="font-size:10px;color:grey;top:20px;">Password must contain Minimum of 6 Characters (At least 1 Alphabet and 1 Digit)</label>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%; vertical-align: top">
                                            <input type="text" class="form-control form-control-lg form-control-alt py-4n @error('employeeid') is-invalid  @enderror " id="employeeid" name="employeeid" placeholder="Employee ID" required>
                                            @error('employeeid')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div id="employeeid-error" class="invalid-feedback" style="display:none;"></div>
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;vertical-align: top">
                                            <select id="dept" name="dept" class="form-control @error('dept') is-invalid @enderror" 
                                              style="font-size: 13px;height:43px;">
                                                <option value="0" selected="selected"> Select a department...</option>
                                                @foreach($departments as $d)
                                                <option value="{{$d->id}}">{{$d->departmentname}}</option>
                                                @endforeach
                                            </select>
                                            @error('dept')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group" style="display:inline-block;width:49%;">                  
                                            <label for="profile_pic" class="form-label @error('profile_pic') is-invalid  @enderror">Profile picture</label>
                                            <input class="form-control" type="file" id="profile_pic" name="profile_pic" accept="image/*">
                                            @error('profile_pic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                <!-- <div class="form-group">
                                    <div class="d-md-flex align-items-md-center justify-content-md-between">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input @error('signup-terms') is-invalid  @enderror" id="signup-terms" name="signup-terms" required>
                                            <label class="custom-control-label font-w400" for="signup-terms">I agree to Terms &amp; Conditions</label>
                                            @error('signup-terms')
                                            <div class="invalid-feedback">You must agree before submitting.</div>
                                            @enderror
                                        </div>
                                        <div class="py-2">
                                            <a class="font-size-sm font-w500" href="javascript:void(0)" data-toggle="modal" data-target="#one-signup-terms">View Terms</a>
                                        </div>
                                    </div>
                                </div> -->
                                <!-- <div class="form-group text-center mb-0">
                                    <button type="submit" class="btn btn-lg btn-alt-success">
                                        <i class="fa fa-fw fa-plus mr-1 opacity-50"></i> Sign Up
                                    </button>
                                    <button type="submit" class="btn btn-lg btn-alt-primary" onclick="loginBtn()">
                                        <i class="fa fa-fw fa-sign-in-alt mr-1 opacity-50"></i>Sign In
                                    </button>

                                </div> -->
                                <div class="form-group" align="center">
                                    <button type="submit" class="btn btn-lg btn-register-success">
                                        <i class="fa fa-fw fa-plus" style="color:white;font-size:11px;vertical-align:middle;"></i> 
                                        <span style="font-size:18px;vertical-align:middle;">Register</span>
                                    </button>
                                </div>
                                </form>
                                <div class="form-group" align="center">
                                    Already have an account? <a href="{{route('getlogin')}}" style="color:#24a88e"><b>Login</b></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 w-100 d-lg-none d-flex flex-column flex-sm-row justify-content-between font-size-sm text-center text-sm-left">
                    <p class="font-w500 text-black-50 py-2 mb-0">
                        <strong>{{ config('app.name') }}</strong> &copy; <span data-toggle="year-copy"></span>
                    </p>
                    <ul class="list list-inline py-2 mb-0">
                        <li class="list-inline-item">
                            <a class="text-muted font-w500" href="javascript:void(0)">Legal</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted font-w500" href="javascript:void(0)">Contact</a>
                        </li>
                        <li class="list-inline-item">
                            <a class="text-muted font-w500" href="javascript:void(0)">Terms</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
</div>
</main>
</div>

<div class="modal fade" id="one-signup-terms" tabindex="-1" role="dialog" aria-labelledby="one-signup-terms" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Terms &amp; Conditions</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content">
                    <p>Password must contain Minimum of 6 Characters (At least 1 Alphabet and 1 Digit).</p>
                </div>
                <div class="block-content block-content-full text-right border-top">
                    <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script type="text/javascript">
    let employeeIdValid = true;
    let typingTimer;
    const doneTypingInterval = 500; // 500ms delay after user stops typing

    function validation() {
        if (document.getElementById('signup-password').value != document.getElementById('signup-password-confirm').value) {
            alert('Password And Confirm Password does not match..Please enter again');
            document.getElementById('signup-password-confirm').focus();
            return false;
        }
        
        // Check if employee ID is valid before submitting
        if (!employeeIdValid) {
            alert('Please enter a unique Employee ID');
            document.getElementById('employeeid').focus();
            return false;
        }
        
        return true;
    }

    function loginBtn() {
        window.location.href = "{{ route('getlogin')}}";
    }

    // Real-time validation for Employee ID
    document.addEventListener('DOMContentLoaded', function() {
        const employeeIdInput = document.getElementById('employeeid');
        const employeeIdError = document.getElementById('employeeid-error');
        
        if (employeeIdInput) {
            employeeIdInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                const employeeId = this.value.trim();
                
                if (employeeId.length === 0) {
                    // Reset validation state if field is empty
                    employeeIdInput.classList.remove('is-invalid');
                    employeeIdError.style.display = 'none';
                    employeeIdValid = true;
                    return;
                }
                
                // Wait for user to stop typing before making API call
                typingTimer = setTimeout(function() {
                    checkEmployeeId(employeeId);
                }, doneTypingInterval);
            });
            
            employeeIdInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });
        }
    });

    function checkEmployeeId(employeeId) {
        const employeeIdInput = document.getElementById('employeeid');
        const employeeIdError = document.getElementById('employeeid-error');
        
        // Make AJAX request to check if employee ID exists
        fetch('/check-employee-id?employeeid=' + encodeURIComponent(employeeId))
            .then(response => response.json())
            .then(data => {
                if (data.available === false) {
                    // Employee ID already exists
                    employeeIdInput.classList.add('is-invalid');
                    employeeIdError.textContent = 'The entered Employee ID is already in use. Please enter a unique Employee ID.';
                    employeeIdError.style.display = 'block';
                    employeeIdValid = false;
                } else {
                    // Employee ID is unique
                    employeeIdInput.classList.remove('is-invalid');
                    employeeIdError.style.display = 'none';
                    employeeIdValid = true;
                }
            })
            .catch(error => {
                console.error('Error checking employee ID:', error);
                // Don't block submission on API error
                employeeIdValid = true;
            });
    }

</script>
@endsection
