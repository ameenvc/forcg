@extends('admin.nav')

@section('content')
    <!-- Page Content -->
    <div class="content">
        <!-- Full Table -->
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title">Add Document Master List</h3>
            </div>
            @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    <a class="close" data-dismiss="alert" style="curson:pointer">×</a>
                        {{ session('error') }} 
                </div>    
            @endif 
            @if (session('success'))
            <div class="alert alert-success" role="alert" >
                <a class="close" data-dismiss="alert" style="curson:pointer">×</a>
                    {{ session('success') }} 
            </div>    
            @endif 
            <div class="block-content block-content-full"  style="width:80%;" > 
                <div class="row">                                
                    <div class="col-lg-12 col-sm-12">                          
                        <form method="post"  action="{{url('/admin/upload/docmasterdetails')}}" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="row">
                                <div class="col-sm-8 mb-2 input-group">
                                    <input type='file' class="form-control" id="example-text-input" style="font-size:13px;" required name="docmaster_details">
                                    <div class="input-group-append">
                                        <input type="submit" value="Upload Details" class="btn btn-primary" style="font-size:10px;">
                                    </div>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <button class="btn  btn-warning" data-id="note" style="font-size:10px;" data-target="#note"  data-toggle="modal" title="View">
                                        Note (*important)
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="modal fade" id="note" tabindex="-1" role="dialog" aria-labelledby="modal-block-fadein" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="block block-rounded block-themed block-transparent mb-0">
                                        <div class="block-header bg-primary-dark">
                                            <h3 class="block-title">*Important</h3>
                                            <div class="block-options">
                                                <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                                                    <i class="fa fa-fw fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="block-content font-size-sm">
                                            <div class="table-responsive">
                                                    <span style="" style="font-size:10px;">1) The Document Master List Details Excel Sheet Should have the Headings ' document, documentnumber,type,level, revisionnumber, revisiondate, department ' respectively and should be in the same sequence.</span>
                                                    <br/><br/><span style="" style="font-size:10px;">2)All Dates Entered Should Be in m/d/Y format. For example : 12/31/2020</span>
                                                    <br/><br/><span style="" style="font-size:10px;">3)Document Master List Details Should Be In Single Excel Sheet.</span>
                                            
                                            </div>
                                        </div>
                                        <div class="block-content block-content-full text-right border-top">
                                            <button type="button" class="btn btn-alt-primary mr-1" data-dismiss="modal" style="font-size:10px;">Close</button>
                                            <button type="button" class="btn btn-primary" data-dismiss="modal" style="font-size:10px;">Ok</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Full Table -->
    </div>
    <!-- END Page Content -->
@endsection