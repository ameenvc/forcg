@extends('admin.datatable')

@section('content')
    <!-- Page Content -->
    <div class="content" >
        <!-- Horizontal -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Edit user</h3>
                <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                    <ol class="breadcrumb breadcrumb-alt">

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="/admin/viewUsers">Back</a>
                        </li>
                    </ol>
                </nav>


            </div>
            <div class="block-content block-content-full" style="width:80%;">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- Form Horizontal - Default Style -->
                        <form class="mb-5" method="POST" action="{{url('/admin/updateUser')}}" onsubmit="return validation()" enctype="multipart/form-data">
                            {{csrf_field()}}
                            @if(Session::has('msg'))
                            <div class="alert alert-primary">
                                <a class="close" data-dismiss="alert">×</a>
                                {!!Session::get('msg')!!}
                            </div>
                            @endif
                            @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger">
                                    <a class="close" data-dismiss="alert">×</a>
                                    {{ $error }}
                                </div>
                            @endforeach
                            @endif
                            <div class="row mb-4">
                                <div class="col-sm-3 ">
                                    <label class="col-form-label" style="font-size: 13px;" for="stage">Name</label>

                                </div>
                                <div class="col-sm-9">
                                    <input type="text" name="name" value="{{$u->name}}" style="font-size: 13px;" class="form-control">

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                    <label style="font-size: 13px;" class="col-form-label" for="title">Email</label>

                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 13px;" name="email" value="{{old('email') ? old('email') :$u->email}}">

                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                <label class="col-form-label" for="issue" style="font-size: 13px;">Employee ID</label>

                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" style="font-size: 13px;" id="empid" name="empid" value="{{$u->employeeid}}">
                                    <div id="empid-error" class="invalid-feedback" style="display:none; color: #e3342f; font-size: 12px;"></div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-sm-3">
                                <label class="col-form-label" for="issue" style="font-size: 13px;">Department</label>


                                </div>
                                <div class="col-sm-9">
                                    <select id="dept" name="dept" class="form-control" style="font-size: 13px;">
                                        <option value="0"> Select a department...</option>
                                        @foreach($department as $d)
                                        <option value="{{$d->id}}" <?php if($u->deptid == $d->departmentname){ ?> selected="selected"<?php } ?>>{{$d->departmentname}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <input type="hidden" name="id" value="{{$u->id}}">
                                    <input type="hidden" style="font-size:13px" id="usermail" name="usermail" value="{{ request()->get('value') }}"></tr>
                                    <input type="submit" name="update" class="btn btn-primary" style="font-size:10px;" value="Update">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Page Content -->
<script>
    let employeeIdValid = true;
    let typingTimer;
    const doneTypingInterval = 500; // 500ms delay after user stops typing
    const originalEmployeeId = "{{$u->employeeid}}"; // Store original employee ID

    function ddlselect() {
        var d = document.getElementById("dept");
        var displaytext = d.options[d.selectedIndex].text;
        document.getElementById("dept").value = displaytext;
    }

    // Real-time validation for Employee ID
    document.addEventListener('DOMContentLoaded', function() {
        const employeeIdInput = document.getElementById('empid');
        const employeeIdError = document.getElementById('empid-error');

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

                // If the employee ID hasn't changed from original, it's valid
                if (employeeId === originalEmployeeId) {
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
        const employeeIdInput = document.getElementById('empid');
        const employeeIdError = document.getElementById('empid-error');
        const userId = "{{$u->id}}"; // Get current user ID

        // Make AJAX request to check if employee ID exists, excluding current user
        fetch('/check-employee-id?employeeid=' + encodeURIComponent(employeeId) + '&exclude_user_id=' + userId + '&edit_user_page=1')
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
<script>
    function validation() {

        // Check if employee ID is valid before submitting
        if (!employeeIdValid) {
            alert('Please enter a unique Employee ID');
            document.getElementById('empid').focus();
            return false;
        }

        return true;
    }

</script>




@endsection
