@extends('layouts.simple')

    @section('content')
                <!-- Page Content -->
                <div class="hero-static">
                    <div class="content">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6 col-xl-4" >
                                <!-- Sign In Block -->
                                <div class="block block-rounded block-themed mb-0">
                                    <div class="block-content">
                                        <div class="p-sm-3 px-lg-4 py-lg-5">
                                            <span> <p>This Page is Blocked due to some reason Please Try after some time later<p></span>
                                        </div>
                                        <div class="form-group row">
                                        <div class="col-md-6 col-xl-5 ml-7">
                                            <a type="submit" class="btn btn-block btn-alt-primary" href='/'>
                                                <i class="fa fa-fw fa-reply mr-1"></i> Back
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                    
                                </div>
                                <!-- END Sign In Block -->
                            </div>
                        </div>
                    </div>
                <!-- END Page Content -->
@endsection
