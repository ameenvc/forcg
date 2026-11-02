{{-- @extends($layout)
@section('content')
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
       </div>
   </div>
    <!-- END Page Content -->
</main> --}}
@extends($layout)

@section('content')

<style>
    .avatar-thumb {
        border: 1px solid #80808059;
        background: #eeeeee36;
        margin-top: 0.5rem;
        height: 8rem;
        width: 100%;
        max-width: 180px;
        margin-left: auto;
        margin-right: auto;
    }

    .avatar-thumb img {
        padding: 1rem;
        object-fit: contain;
        width: 100%;
        height: 100%;
    }

    .profile-content {
        width: 100%;
    }

    .profile-form-container {
        width: 100%;
    }

    .profile-form-container .form-group {
        margin-bottom: 1rem;
    }

    /* Tablet */
    @media (max-width: 991.98px) {

        .page-content-wrapper {
            width: 100% !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
        }

        .block-content-wrapper {
            width: 100% !important;
            margin-left: 0 !important;
        }

        .profile-form-container {
            padding: 0 15px;
        }

        .avatar-container {
            margin-top: 20px;
        }
    }

    /* Mobile */
    @media (max-width: 767.98px) {

        .block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .block-title {
            font-size: 18px;
        }

        .back-navigation {
            margin-left: 0 !important;
        }

        .profile-form-container {
            padding: 0;
        }

        .profile-form-container .form-group.row {
            display: block;
        }

        .profile-form-container .form-group.row label,
        .profile-form-container .form-group.row .col-sm-8 {
            width: 100%;
            max-width: 100%;
        }

        .profile-form-container label {
            margin-bottom: 5px;
        }

        .profile-form-container .form-control {
            width: 100%;
        }

        .update-button {
            justify-content: center !important;
        }

        .avatar-container {
            margin-top: 25px;
        }

        .avatar-thumb {
            max-width: 160px;
            height: 160px;
        }
    }

    /* Very small screens */
    @media (max-width: 575.98px) {

        .block-content {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }

        .block-header {
            padding-left: 15px;
            padding-right: 15px;
        }

        .breadcrumb {
            margin-bottom: 0;
        }

        .avatar-thumb {
            max-width: 140px;
            height: 140px;
        }

        .update-button .btn {
            width: 100%;
            max-width: 150px;
        }
    }
</style>

<main id="main-container" style="padding: 0;">

    <div class="content page-content-wrapper">

        <div class="block block-rounded">

            <!-- Header -->
            <div class="block-header">

                <h3 class="block-title">
                    Edit Profile
                </h3>

                <nav class="flex-sm-00-auto back-navigation"
                     aria-label="breadcrumb">

                    <ol class="breadcrumb breadcrumb-alt mb-0">

                        <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx"
                               href="#"
                               onclick="history.back(); return false;">
                                Back
                            </a>
                        </li>

                    </ol>

                </nav>

            </div>


            <!-- Content -->
            <div class="block-content block-content-full block-content-wrapper">

                <!-- Success Message -->
                @if(Session::has('msg'))

                    <div class="alert alert-success">
                        <a href="javascript:void(0)"
                           class="close"
                           data-dismiss="alert">
                            ×
                        </a>

                        {!! Session::get('msg') !!}
                    </div>

                @endif


                <!-- Validation Errors -->
                @if(count($errors) > 0)

                    @foreach($errors->all() as $error)

                        <div class="alert alert-warning">

                            <a href="javascript:void(0)"
                               class="close"
                               data-dismiss="alert">
                                ×
                            </a>

                            {{ $error }}

                        </div>

                    @endforeach

                @endif


                <!-- Profile Row -->
                <div class="row align-items-start">

                    <!-- Form -->
                    <div class="col-12 col-lg-9">

                        <div class="profile-form-container">

                            <form class="mb-5"
                                  method="POST"
                                  action="{{ url('profile/update') }}"
                                  onsubmit="return validation()"
                                  enctype="multipart/form-data">

                                {{ csrf_field() }}

                                <div class="row">

                                    <div class="col-12 col-md-10 offset-md-1">

                                        <!-- Name -->
                                        <div class="form-group row">

                                            <label class="col-12 col-sm-4 col-form-label"
                                                   style="font-size:13px;"
                                                   for="name">
                                                Name
                                            </label>

                                            <div class="col-12 col-sm-8">

                                                <input type="text"
                                                       id="name"
                                                       name="name"
                                                       value="{{ $users[0]->name }}"
                                                       style="font-size:13px;"
                                                       class="form-control"
                                                       readonly>

                                            </div>

                                        </div>


                                        <!-- Email -->
                                        <div class="form-group row">

                                            <label class="col-12 col-sm-4 col-form-label"
                                                   style="font-size:13px;"
                                                   for="email">
                                                Email
                                            </label>

                                            <div class="col-12 col-sm-8">

                                                <input type="text"
                                                       id="email"
                                                       class="form-control"
                                                       style="font-size:13px;"
                                                       name="email"
                                                       value="{{ $users[0]->email }}"
                                                       readonly>

                                            </div>

                                        </div>


                                        <!-- Employee ID -->
                                        <div class="form-group row">

                                            <label class="col-12 col-sm-4 col-form-label"
                                                   style="font-size:13px;"
                                                   for="dept_id">
                                                Employee ID
                                            </label>

                                            <div class="col-12 col-sm-8">

                                                <input type="text"
                                                       class="form-control"
                                                       style="font-size:13px;"
                                                       id="dept_id"
                                                       name="empid"
                                                       value="{{ $users[0]->employeeid }}"
                                                       readonly>

                                            </div>

                                        </div>


                                        <!-- Profile Picture -->
                                        <div class="form-group row">

                                            <label class="col-12 col-sm-4 col-form-label"
                                                   style="font-size:13px;"
                                                   for="profile_picture">
                                                Profile picture
                                            </label>

                                            <div class="col-12 col-sm-8">

                                                <input type="file"
                                                       class="form-control"
                                                       accept="image/*"
                                                       style="font-size:13px;"
                                                       id="profile_picture"
                                                       name="profile_pic">

                                            </div>

                                        </div>


                                        <!-- Hidden ID -->
                                        <input type="hidden"
                                               name="id"
                                               value="{{ $users[0]->employeeid }}">


                                        <!-- Update -->
                                        <div class="form-group d-flex justify-content-end update-button">

                                            <input type="submit"
                                                   name="update"
                                                   class="btn btn-primary"
                                                   style="font-size:10px;"
                                                   value="Update">

                                        </div>

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>


                    <!-- Profile Image -->
                    <div class="col-12 col-lg-3 avatar-container">

                        <div class="avatar-thumb">

                            @if (
                                $users[0]->profile_picture &&
                                file_exists(
                                    public_path(
                                        '/storage/profile/' .
                                        $users[0]->profile_picture
                                    )
                                )
                            )

                                <img id="imgPreview"
                                     src="{{ asset('storage/profile/' . $users[0]->profile_picture) }}"
                                     alt="{{ $users[0]->name }}">

                            @else

                                <img id="imgPreview"
                                     src="{{ asset('/images/avatar10.jpg') }}"
                                     alt="{{ $users[0]->name }}">

                            @endif

                        </div>


                        <div class="text-center mt-2">

                            <a href="{{ route('profile.removeAvatar') }}"
                               class="text-danger"
                               style="font-size:14px;">
                                Delete Image
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- END Main Container -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
<script>
    $('i.fa-cubes').closest('button').hide();
</script>
@endsection
