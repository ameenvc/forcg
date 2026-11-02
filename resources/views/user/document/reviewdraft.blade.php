@extends('layouts.backend')
@section('exportadd')
<div class="input-group input-group-sm">
    <a href="" class="btn btn-sm btn-dual" style="margin-left:0px;" onclick="return fnCovertTable('print')">
        <i class="fa fa-print  fa-1x" style="color:grey"></i>
    </a>
</div>
<div class="input-group input-group-sm">
    <button type="button" class=" btn-sm btn-dual btn btn-default dropdown-toggle mr-0 mb-0" style="margin-left:8px; " data-toggle="dropdown">
        <i class="fa fa-file-export fa-1x" style="color:grey;"></i>
    </button>
    <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
        <li>
            <!--change route as below-->
            <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                @csrf
                <button onclick="return fnCovertTable('pdf')" style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                <!--change hidden input type id as below-->
                <input type="hidden" id="tvalue3" name="tvalue3" />
            </form>
        </li>
        <li><button onclick="fnCovertTable('excel')" style="width:60px;  font-size:10px;border:none;background-color:white;">Excel</button></li>
        <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>

    </ul>
</div>
@endsection
@section('content')
<!doctype html>
<html lang="en">

<head>
    <script async charset="utf-8" src="//cdn.embedly.com/widgets/platform.js"></script>
    <style>
        

        .rich_text_container table tr td {
            border: 1px solid #6f7171 !important;
        }
        .block-content .block, .block-content .items-push > div, .block-content .push, .block-content p {
            margin-bottom: 0;
        }
        .loading-process {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            background: #eeeeee5e;
            z-index: 1;
        }
        .loading-process div {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    @php
    $data=$data1=[];
    @endphp
    <!-- Page Content -->
    <div class="content" style="width:97%;">
        @if(Session::has('msg'))
        <div class="alert alert-danger">
            <a class="close" data-dismiss="alert">×</a>
            {!!Session::get('msg')!!}
        </div>
        @endif
        <!-- Discussion -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a class="link-fx btn btn-sm btn-alt-primary" href="{{ route('doc.document_draft')}}"><i style="font-size:1.3em;color: #3366CC;" class="fa fa-2x fa-arrow-left"></i></a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <h3 class="block-title text-center" style="font-size:1.1em;color: #3366CC;word-wrap: break-word;padding-left:10%;padding-right:10%;">{{$docname->document_name}}  </h3>
                <div class="block-options" >
                    <div class="input-group input-group-sm" style="font-size:1em;">
                        <button type="button" class=" btn-sm btn btn-alt-primary dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-file-export fa-1x" style="color: #3366CC;"></i>
                        </button>
                        <ul class="dropdown-menu" role="menu" style="min-width:10px;text-align:center;">
                            <li>
                                <!--change route as below-->
                                <form method="post" action="{{url('/user/convert/file/'.'tvalue3')}}" style="margin-bottom: 0px">
                                    @csrf
                                    <button onclick="return fnCovertTable('pdf')"
                                        style="width:60px;  font-size:10px;border:none;background-color:white;">Pdf</i></button>
                                    <!--change hidden input type id as below-->
                                    <input type="hidden" id="tvalue3" name="tvalue3" />
                                </form>
                            </li>
                            <li><button onclick="fnCovertTable('word')" style="width:60px;  font-size:10px;border:none;background-color:white;">Word</button></li>
                        </ul>
                        &nbsp;&nbsp;
                        <button type="button" class="btn-block-option" data-toggle="block-option" data-action="fullscreen_toggle"></button>
                    </div>
                    
                </div>
            </div>
            <div class="block-content">
                @if(count($documentContents)>0)
                <form>
                    <div class="table-responsive">
                        <table class="table" style="border: 1px solid #D3D3D3;">
                            <tbody>
                                @php $i = 0; $n = 1; @endphp
                                @foreach ($documentContents as $documentContent)
                                @php 
                                    $contents = \App\GovernanceHubDraftContentRichText::where('content_id',$relatedDocumentIds[$i])
                                    ->orderBy('position', 'asc')->get();
                                @endphp
                                <tr class="table-active" >
                                    <td class="d-none d-sm-table-cell text-center" style="width:50px;border-right: 1px solid #D3D3D3;">
                                        <span style="color: #3366CC;font-size:13px;" class="font-w600"><?php printf("%.1f", $n); ?></span>
                                    </td>
                                    <td class=" d-none d-sm-table-cell text-left" style="width:230px;border-right: 1px solid #D3D3D3;">
                                        <span class="font-w600 text-uppercase">Responsibility</span>
                                    </td>
                                    <td class="text-left">
                                        <span class="font-w600">{{ucfirst($documentContent->title)}}</span>
                                    </td>   
                                </tr>
                                @php $m = 1; @endphp
                                @foreach($contents as $con)
                                    <tr>
                                        <td style="border-right: 1px solid #D3D3D3;position: relative;" class="d-none d-sm-table-cell text-center">
                                            <span style="font-size:13px;">@php echo $n . '.' . $m; $m++; @endphp</span>
                                        </td>
                                        <td style="border-right: 1px solid #D3D3D3;" class="d-none d-sm-table-cell text-left">
                                            @if(isset($con->responsibility))
                                                <span style="word-wrap:break-word;font-size:13px;"><b>{{ucfirst($con->responsibility)}}</b></span>
                                            @else
                                                <span>-</span>
                                            @endif
                                        </td>
                                        <td class="text-left" colspan="3">
                                            <div class="rich_text_container">
                                                @if(isset($con->rich_text))
                                                    <span style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">{!! $con->rich_text!!}</span>
                                                @else
                                                    <span>-</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                <?php $i++; $n++;?>
                                @endforeach
                                <script>
                                document.querySelectorAll('oembed[url]').forEach(element => {
                                    // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
                                    // to discover the media.
                                    const anchor = document.createElement('a');

                                    anchor.setAttribute('href', element.getAttribute('url'));

                                    anchor.className = 'embedly-card';

                                    element.appendChild(anchor);

                                });
                                </script>
                            </tbody>
                        </table>
                    </div>
                        
                    <input type="hidden" name="id" value="{{$id}}" id="id" class="form-control">
                    <div class="row mr-1">
                        <div class="form-group col-sm-12 text-right">
                            <a href="{{ route('doc.reviewrejectdraft', ['id' => $id]) }}" class="btn btn-sm btn-white text-danger border border-danger" style="font-size:13px;"
                               onclick="return confirm('Are you sure you want to reject this?')">Reject</a>
                            &nbsp;
                            <!-- <button type="submit" class="btn btn-sm btn-primary" onclick="requestAccept()">Accept</button> -->
                            <!-- <a href="{{ route('doc.reviewacceptdraft', ['id' => $id]) }}" class="btn btn-sm btn-primary">Accept</a> -->
                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" id="dropdown-default-primary" 
                             style="font-size:13px;" data-toggle="dropdown">Accept</button>
                                <div class="dropdown-menu font-size-sm" aria-labelledby="dropdown-dropleft-outline-dark">
                                  <a href="{{ route('doc.reviewacceptdraft', ['id' => $id]) }}" class="dropdown-item" style="font-size:11px;color:black;">
                                    <i class="fas fa-check-circle fa-1x text-primary"></i>&nbsp;&nbsp;Accept
                                  </a>
                                  <a href="#" class="dropdown-item" style="font-size:11px;" data-toggle="modal"  data-target="#reviewacceptremarks" onclick="getidvalue('{{$id}}')">
                                  <i class="fa fa-1x fa-edit text-secondary"></i>&nbsp;&nbsp;Accept with remarks</a>
                                </div>
                        </div>
                    </div>
                </form>
                @else
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <p style="font-size: 20px;">There are no contents inside the draft.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- END Page Content -->
    <!-- accept remarks starts-->
<div class="modal fade" id="reviewacceptremarks" tabindex="-1" role="dialog" aria-labelledby="reviewacceptremarksModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewacceptremarksModalLabel">Add Remarks</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add_review_accept_remarks_ajax" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="loading-process d-none">
                        <div><img src="{{ asset('images/loading.gif') }}" style="width: 64px"></div>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success" id="remarksaddedsuccess" style="display:none">
                            Remarks added successfully
                        </div>

                        <ul class="alert alert-danger" id="remarksaddeddanger" style="font-size:12px;display:none">
                               Internal server error, Unable to process request
                        </ul> 

                        <ul class="alert alert-danger" id="remarksdangernotadded" style="display:none"></ul>
                        <ul class="alert alert-warning" id="remarksaddedwarning" style="font-size:12px;display:none"></ul>
                        <div class="form-group">
                            <label for="remarks" class="col-sm-12 col-form-label" style="font-size:12px;">Remarks <span style="color:red">*</span>
                            </label>
                            <div class="col-sm-12">
                                <textarea class="form-control" style="font-size:12px;" name="review_accept_remarks"
                                    id="review_accept_remarks" placeholder="Please enter the remarks" required></textarea>
                            </div>
                        </div>                       
                    </div>
                    <input type="hidden" name="main_draft_id" id="main_draft_id"/>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="font-size:12px">Close</button>
                        <button type="submit" class="btn btn-primary" style="font-size:12px">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- accept remarks  ends-->
    
    @include('user.document.governance_hub_headers')

    <script>
        function getidvalue(id){
            $('#main_draft_id').val(id);
        }
        window.onload = function() {
            $(document).ready(function() {
                $('#add_review_accept_remarks_ajax').submit(function(event) {
                    event.preventDefault();
                    $('.loading-process').removeClass('d-none');
                    $(this).find('button[type="submit"]').prop('disabled', true);
                    var formData = $(this).serialize();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                            type: 'POST',
                            url: '/user/doc/review-accept-remarks-draft',
                            data: formData
                            ,success: function(response) {
                            $('.loading-process').addClass('d-none');
                                if(response.status == 200)
                                {
                                    $('#remarksaddeddanger').hide();
                                    $('#remarksdangernotadded').hide();
                                    $('#remarksaddedsuccess').css('display', 'block');
                                    if(response.msg) {
                                        $('#remarksaddedwarning').html(response.msg);
                                        $('#remarksaddedwarning').css('display', 'block');
                                    }
                                    setTimeout(
                                        function() {
                                            window.location.href = '/user/doc/document-draft';
                                        }, 2000);
                                }
                                else if(response.status == 422){                              
                                    var error_text='';                                                           
                                      $.each(response.error, function(key, val) {
                                            error_text += '<li style="font-size:12px;">' + val + '</li>';
                                        });                           
                                    $('#remarksdangernotadded').html(error_text);
                                    $('#remarksaddeddanger').hide();
                                    $('#remarksdangernotadded').css('display', 'block');                              
                                }
                                else if(response.status == 404){                              
                                    $('#remarksdangernotadded').hide();
                                    $('#remarksaddeddanger').css('display', 'block');                            
                                }
                                else if(response.status == 500){                              
                                    $('#remarksdangernotadded').hide();
                                    $('#remarksaddeddanger').css('display', 'block');                            
                                }
                            }
                        ,error: function(error) {
                            $('.loading-process').addClass('d-none');
                            $('#remarksdangernotadded').hide();
                            $('#remarksaddeddanger').css('display', 'block');
                        }
                    });              
                });
            });
        }
    </script>
   
    <!-- <script>
        function requestAccept() {
            if (confirm('Are you sure you want to proceed with this action?')) {
                var formData = new FormData();
                formData.append('id', $(id).val());
                $.ajax({
                    url: '/user/doc/review-accept-draft',
                    type: 'POST',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    error: function(err) {
                        if (err.status === 500) {
                            $("#error").append(`<li>Internal server error, Unable to process request</li>`);
                        } else if (err.status === 422) {
                            for (let i in err.responseJSON.errors) {
                                $("#error").append(`<li>${err.responseJSON.errors[i]}</li>`);
                            }
                        }
                    },
                });
            }
        }
    </script> -->
</body>

</html>
@endsection