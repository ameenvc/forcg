@extends('superadmin.datatable')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <!-- Main Container -->

                <!-- END Hero -->
                <div class="bg-body-light">
                    <div class="content content-full">
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center" style="height:0px;">
                           
                            <nav class="flex-sm-00-auto ml-sm-3" aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-alt">
                                <li class="breadcrumb-item">Backup/Restore Data</li>
                                    <li class="breadcrumb-item" aria-current="page">
                                    <a id="a1" onclick="" class="link-fx" href="/superadmin/home">Back</a>
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- END Hero -->

                <div class="row">
                        <div class="col-sm-5" style="margin-left:95px;margin-top:30px">
                            <div class="block block-rounded invisible" data-toggle="appear">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center ">
                                       <label style="custom-control-label font-w600 font-size-sm text-muted" style="font-size:13px;">Backup</label>
                                        <div class="font-size-h4 font-w600 pt-3 mb-0 text-muted">
                                            <form action="{{ url('backup/create') }}" method="post">
                                            @csrf
                                            <div class="form-group" style="">
                                            <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;margin-left:-10px;">
                                            <input type="checkbox" onclick="checked()" class="custom-control-input" id="db" name="db">
                                            <label class="custom-control-label custom-control-label font-w600 font-size-sm text-muted" style="font-size:13px;"for="db" style="font-size:13px;">Backup Database</label>
                                            </div>
                                            <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;margin-left:50px;">
                                            <input type="checkbox" onclick="checked()" class="custom-control-input" id="file" name="file">
                                            <label class="custom-control-label custom-control-label font-w600 font-size-sm text-muted" style="font-size:13px;" for="file" style="font-size:13px;">Backup Files</label>
                                            </div>

                                            <div class="input-group">
                                            <button type="submit" onclick="return backup()" style="font-size:13px;margin-left:130px;" id="create-new-backup-button"  class="btn btn-primary" >Creat Backup +</button>
                                            </div>
                                            </div>
                                            </form>

                                        
                                        <span></span></div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5" style="float:right;margin-top:30px;">
                            <div class="block block-rounded invisible" data-toggle="appear" style="height:189px;">
                                <div class="block-content block-content-full">
                                    <div class="py-2 text-center">
                                    <label style="custom-control-label font-w600 font-size-sm text-muted" style="font-size:13px;"> Restore</label>
                                        <div class="font-size-h4 font-w600 pt-3 mb-0 text-muted">
                                        
                                        <form method="POST" action="{{route('backup/restore')}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                        <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;margin-left:-10px;" >
                                        <input type="checkbox" onchange="restoredb()"  name="rdb"  id="rdb" class="custom-control-input" >
                                        <label class="custom-control-label custom-control-label font-w600 font-size-sm text-muted"  for="rdb" style="font-size:13px;">Restore Database</label>
                                        </div>
                                        <div class="custom-control custom-checkbox custom-control-dark mb-0" style="display:inline;margin-left:50px;">
                                        <input type="checkbox" onchange="restorefile()"  class="custom-control-input" id="rfile" name="rfile" >
                                        <label class="custom-control-label custom-control-label font-w600 font-size-sm text-muted" for="rfile" style="font-size:13px;">Restore Files</label>
                                        </div>
                                        </div>

                                        <br/>
                                        <br/>
                                        <input type="file" style="display:none;" name="resfile" id="restore-File"  webkitdirectory directory multiple  />
                                            <input type="text" class="form-control form-control-alt" id="dbname" name="dbname" style="font-size:13px;height:35px;width:180px;display:none;margin-right:190px;float:right;margin-top:-85px;" placeholder="Enter DataBase Name"> 
                                            <input type="file" id="restore-DB" onchange="check()" name="resdb" class="btn btn-primary pull-right "
                                            style="margin-right:50%;display:none;float:right"/>
                                            <label for="restore-DB" id="fordb" style="float:right;display:none;margin-top:-85px;font-size:13px;margin-right:0px;" class="btn btn-primary pull-right " >Please Choose DB File</label>
                                            <br/>
                                            <br/>

                                        <button  id="restoreit"  class="btn btn-primary"
                                            style="margin-bottom:2em;display:none;float:right;margin-top:-100px;font-size:13px;margin-right:140px;" value="0" onclick="return restore()"><i
                                                    class="fa fa-undo"></i><span style="font-size:12px;">Restore</span>
                                            </button>
                                        </form>
                                        <div class="container" style="float:right;">
                                        <form method="POST" action="{{route('backup/restorefile')}}" enctype="multipart/form-data" id="myForm">
                                        @csrf
                                        <input type="file" id="files" name="files[]" multiple="" webkitdirectory="" style="display:none;">​
                                        <input type="hidden" id="hd" name="hd" value="">
                                        <label for="files" id="forfile" style="font-size:13px;float:right;display:none;margin-right:25px;margin-top:-144px;margin-right:90px;" class="btn btn-primary pull-right " >Please Choose Floder</label>
                                        <br/><br/>
                                        <button onclick="return restore()" class="btn btn-primary pull-right" id="btnrfile"
                                        style="margin-bottom:2em;display:none;float:right;margin-right:130px;margin-top:-160px;font-size:13px;" value="0"><i class="fa fa-undo"></i><span style="font-size:12px;">Restore</button></span>
                                        </form>
                                        <span></span></div>
                                    </div>
                                </div>
                            </div>
                        <!-- </div>-->
                    </div >
              
</div>



                            <!-- All Orders Table -->
                            @if (count($backups))
                            <!-- Page Content -->
                            <div class="content"  style="width:92%;margin-left:50px;margin-top:-115px;">
                    <!-- Dynamic Table Full -->
                    <div class="block block-rounded" >
                        {{-- <div class="block-header">
                            <h3 class="block-title">Dynamic Table <small>Full</small></h3>
                        </div> --}}
                        <div class="block-content block-content-full" style=" overflow-y:scroll;height:450px;display:block;">
                            <!-- DataTables init on table by adding .js-dataTable-full class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->                            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                                <thead>
                                        <tr>
                                            <th style="font-size:10px;" class="text-center" style="width: 100px;" >SL No.</th>
                                            <th class="d-none d-sm-table-cell text-center" style="font-size:10px;">Zip File(DB/FILES)</th>
                                            <th class="d-none d-sm-table-cell text-center" style="font-size:10px;">Size</th>
                                            <th class="text-center"  style="font-size:10px;">Date</th>
                                            <th class="text-center" style="font-size:10px;" >Time</th>
                                            <th class="text-center"  style="font-size:10px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0 ?>
                                    @foreach($backups as $backup)
                                        <tr>
                                        <td class="text-center" style="font-size:10px;">{{$i=$i+1}}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $backup['file_name'] }}</td>
                                        <td class="text-center" style="font-size:10px;">{{ $backup['file_size'] }}</td>
                                        <td class="text-center" style="font-size:10px;">
                                            {{$backup['date'] }}
                                        </td>
                                        <td class="text-center" style="font-size:10px;">
                                            {{$backup['time']}}
                                        </td>
                                        <td class="text-center">
                                                <a class="btn btn-sm btn-alt-secondary" href="{{ url('backup/download/'.$backup['file_name']) }}" data-toggle="tooltip" title="Download">
                                                    <i class="fa fa-download" style="font-size:10px;"></i>
                                                </a>
                                                <a class="btn btn-sm btn-alt-danger" onclick="return deleteit()" href="{{ url('backup/delete/'.$backup['file_name']) }}" data-toggle="tooltip" title="Delete">
                                                    <i class="fa fa-fw fa-times text-danger" style="font-size:10px;"></i>
                                                </a>
                                            </td>
                                           
                                        </tr>
                                 @endforeach
                                 </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                    <!-- END Dynamic Table Full -->

                   
                </div>
                
                <!-- END Page Content -->
<!-- END Main Container -->



 <script type="text/javascript">
    window.onload = function(){
	// var output = document.getElementById('output');
	
		// Detect when the value of the files input changes.
		document.getElementById('files').onchange = function(e) {
			// Retrieve the file list from the input element
			uploadFiles(e.target.files);
			
			// Outputs file names to div id "output"
			// output.innerText = "";
			// for (var i in e.target.files)
			// 	output.innerText  = output.innerText + e.target.files[i].webkitRelativePath+"\n";
		}
	}
	
	
	function uploadFiles(files){
	
		// // Create a new HTTP requests, Form data item (data we will send to the server) and an empty string for the file paths.
		// let xhr = new XMLHttpRequest();
		// data = new FormData();
		paths = "";
		
		// // Set how to handle the response text from the server
		// xhr.onreadystatechange = function(ev){
		// 	console.debug(xhr.responseText);
		// };
		// var fd = document.getElementById("file");

		// Loop through the file list
		for (var i in files){
			// Append the current file path to the paths variable (delimited by tripple hash signs - ###)
			paths += files[i].webkitRelativePath+"###";
			// Append current file to our FormData with the index of i
			// data.append(i, files[i]);
			// fd.append('file[]', files[i]);
		};
		document.getElementById('hd').value=paths;		
	}

    function backup()
    {
        if(!document.getElementById('db').checked && !document.getElementById('file').checked)
        {alert('please choose atlease one BackUp option');
        return false;
        }
        return true;
    } 

    function deleteit(){
        return confirm("Do you really want to delete this BackUp?");
    }

   function restoredb(){
       if(document.getElementById('rfile').checked){
       alert("please choose only on Restore Option")
       document.getElementById('rdb').checked=false;
       document.getElementById('rfile').checked=false;
       document.getElementById('fordb').style.display="none";
       document.getElementById('forfile').style.display="none";
       document.getElementById('dbname').style.display="none";
       document.getElementById('restoreit').value=0;
       document.getElementById('dbname').value==""
       document.getElementById('restore-DB').value=""
       document.getElementById("restoreit").style.display="none"
       document.getElementById('btnrfile').style.display="none"
       }
       else{
           document.getElementById('fordb').style.display="";
           document.getElementById('dbname').style.display="";
           document.getElementById('restoreit').value=1;
           document.getElementById('forfile').style.display="none";
           document.getElementById('restore-File').value=""
           document.getElementById("restoreit").style.display=""
           document.getElementById('btnrfile').style.display="none"
       }
   }

   function restorefile(){
    if(document.getElementById('rdb').checked){
       alert("please choose only on Restore Option")
       document.getElementById('rfile').checked=false;
       document.getElementById('rdb').checked=false;
       document.getElementById('forfile').style.display="none";
       document.getElementById('fordb').style.display="none";
       document.getElementById('dbname').style.display="none";
       document.getElementById('restoreit').value=0;
       document.getElementById('restore-File').value=""
       document.getElementById("restoreit").style.display="none"
       document.getElementById('btnrfile').style.display="none"
       }
       else{
        document.getElementById('forfile').style.display="";
        document.getElementById('restoreit').value=1;
        document.getElementById('fordb').style.display="none";
        document.getElementById('dbname').style.display="none";
        document.getElementById('restore-DB').value="";
        document.getElementById('dbname').value="";
        document.getElementById("restoreit").style.display="none"
       document.getElementById('btnrfile').style.display=""
       }

   }

function check(){
file=document.getElementById('restore-DB');
name=file.files.item(0).name;
ext=name.substring(name.lastIndexOf('.'));
if(ext==".sql")
document.getElementById('fordb').innerHTML="SQL file is Selected";
else{
alert("Please Select only SQL File");
document.getElementById('fordb').innerHTML="Please Select SQL File";
}
}

    function restore()
    {
        if(document.getElementById('restoreit').value=="0")
        {alert("Please Choose Restore Option");
        return false;
        }
        else
        {
        if(document.getElementById('rdb').checked)
        {
           if(document.getElementById('dbname').value=="") 
           {
           alert("Please Enter DB name");
           return false;
           }
           else if(!document.getElementById('restore-DB').value){
           alert("Please Choose .SQL file to restore")
           return false
           }
           else
           return confirm('do you really want to restore backup'); 

        }
       else if(document.getElementById('rfile').checked){
           if(!document.getElementById('files').value)
           {
           alert("Please Choose Store Folder  to restore")
           return false;
           }
           else{
            x=document.getElementById('files');
               paths="";
               if('files' in x){
               for(var i=0;i<x.files.length;i++){
                   var file=x.files[i].webkitRelativePath+"###";
                   paths+=file;
               }
               document.getElementById('hd').value=paths;
            }
           return confirm('do you really want to restore backup');
           }
       }

        }
        return true;
    }

    function check1(){
        alert()
        return true;
    }
    // window.onload = function(){
	// // var output = document.getElementById('output');
	
	// 	// Detect when the value of the files input changes.
	// 	document.getElementById('files').onchange = function(e) {
	// 		// Retrieve the file list from the input element
	// 		uploadFiles(e.target.files);
	// 		alert("hello")
	// 		// Outputs file names to div id "output"
	// 		// output.innerText = "";
	// 		// for (var i in e.target.files)
	// 		// 	output.innerText  = output.innerText + e.target.files[i].webkitRelativePath+"\n";
	// 	}
	// }
	
	
	// function uploadFiles(files){
	
	// 	// // Create a new HTTP requests, Form data item (data we will send to the server) and an empty string for the file paths.
	// 	// let xhr = new XMLHttpRequest();
	// 	// data = new FormData();
	// 	paths = "";
		
	// 	// // Set how to handle the response text from the server
	// 	// xhr.onreadystatechange = function(ev){
	// 	// 	console.debug(xhr.responseText);
	// 	// };
	// 	// var fd = document.getElementById("file");

	// 	// Loop through the file list
	// 	for (var i in files){
	// 		// Append the current file path to the paths variable (delimited by tripple hash signs - ###)
	// 		paths += files[i].webkitRelativePath+"###";
	// 		// Append current file to our FormData with the index of i
	// 		// data.append(i, files[i]);
	// 		// fd.append('file[]', files[i]);
    //     };
    //     alert('hello2')
	// 	document.getElementById('hd').value=paths;		
	// }
    </script>

@endsection