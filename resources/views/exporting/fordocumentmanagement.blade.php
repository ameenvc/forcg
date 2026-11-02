@if( count($data) > 0)
                            <?php 
                            //dd($data); ?>
                            <table  class="table  table-bordered table-striped table-vcenter "  id="headerTable1" style="display:none;">
                               <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize" ><span style="font-size:10px;width: 50px;text-transform:capitalize">&nbspDocument ID&nbsp</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Department</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Requester Name</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Document Type</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Type of Action</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">&nbsp&nbspProcess&nbspStage&nbsp&nbsp</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Added date</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Reviewed Date</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Requester ID</span></th> 
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Remarks</span></th> 
                                    </tr>
                                   
                                </thead>
                                <?php $no=0;
                                ?>
                                @foreach($data as $value)
                                <?php 
                                 $rr=$value->id;?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->doc_id }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->department }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->name }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->doctype }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->type}}</span></td>
                                            @if($value->status=='Pending')
                                                        <td class="text-warning "style="font-size:12px;width: 50px;background-color:yellow"><span  style="font-size:10px;width: 50px;color:yellow">{{ $value->status}}</span></td>
                                                        @endif
                                                        @if($value->status=='Approved')
                                                        <td class="text-success "style="font-size:12px;width: 50px;background-color:green"><span  style="font-size:10px;width: 50px;color:green">{{ $value->status}}</span></td>
                                                        @endif
                                                        @if($value->status=='Disapproved')
                                                        <td class="text-danger "style="font-size:12px;width: 50px;background-color:red"><span  style="font-size:10px;width: 50px;color:red">{{ $value->status}}</span></td>
                                                        @endif
                                                        @if($value->status=='Reviewed OK')
                                                        <td class="text-info "style="font-size:12px;width: 50px;background-color:black"><span  style="font-size:10px;width: 50px;color:black">{{ $value->status}}</span></td>
                                                        @endif
                                                        @if($value->status=='Reviewed Not OK')
                                                        <td class="text-danger "style="font-size:12px;width: 50px;background-color:red"><span  style="font-size:10px;width: 50px;color:red">{{ $value->status}}</span></td>
                                                        @endif
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->date  }}</span></td>  
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewed_at }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->postedby }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reason }}</span></td>
                                        </tr>
                                   
                                </tbody>
                            @endforeach
                            
                        </table>
                        @endif

                        @if( count($data1) > 0)
                            <?php 
                            //dd($data); ?>
                            <table  class="table  table-bordered table-striped table-vcenter "  id="headerTable2" style="display:none;">
                               <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:10px;width: 50px;text-transform:capitalize">Document Number</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize" ><span style="font-size:10px;width: 50px;text-transform:capitalize">&nbspDocument&nbsp</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Revision Number</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Revision Date</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Type</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Level</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Department</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Process Stage</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Added Date</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Reviewed Data</span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Posted By(Employee ID)</span></th>
                                    </tr>
                                   
                                </thead>
                                <?php $no=0;
                                ?>
                                @foreach($data1 as $value)
                                <?php 
                                 $rr=$value->id;?>
                                    <tbody>
                                        <tr>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->docnumber }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->document }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->revnumber }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->erevdate }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->doctype ? $value->doctype->typename : $value->type}}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->level }}</span></td> 
                                            
                                           
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->department }}</span></td> 
                                                        @if($value->status=='-')
                                                        <td class="text-center "style="font-size:12px;width: 50px;"><span class="text-center" style="font-size:10px;width: 50px;">{{ $value->status}}</span></td>
                                                        @elseif($value->status=='Change Request Approved' )
                                                        <td class="text-success "style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:green;">{{ $value->status}}</span></td>
                                                        @elseif($value->status=='Change Request Disapproved'|| $value->status=='Removal Request Disapproved')
                                                        <td class="text-danger "style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:red;">{{ $value->status}}</span></td>
                                                        @elseif($value->status=='Change Request Raised' || $value->status=='Removal Request Raised')
                                                        <td class="text-warning "style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:yellow;">{{ $value->status}}</span></td>
                                                        @else
                                                        <td class="text-warning "style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:yellow;">{{ $value->status}}</span></td>
                                                        @endif
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->createdat }}</span></td> 
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reviewedat }}</span></td> 
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->postedby }}</span></td> 
                                        </tr>
                                   
                                </tbody>
                            @endforeach
                            
                        </table>
                        @endif

                        @if( count($data2) > 0)
                        <?php 
                        //dd($data); ?>
                        <table  class="table  table-bordered table-striped table-vcenter "  id="headerTable3" style="display:none;">
                           <thead>
                                <tr>
                                <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Type</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize" ><span style="font-size:10px;width: 50px;text-transform:capitalize">Document ID</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize" ><span style="font-size:10px;width: 50px;text-transform:capitalize">Document Name</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Document Number</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Revision Number</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Department</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Requested Date</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:10px;width: 50px;text-transform:capitalize">Approved Date</span></th>
                                    
                                </tr>
                               
                            </thead>
                            <?php $no=0;
                            ?>
                             <?php $no=0;
                                    $a="Add Request";
                                    $b="Change Request";
                                    $c="Removal Request";
                            ?>
                            @foreach($data2 as $value)
                            <?php 
                             $rr=$value->id;?>
                                <tbody>
                                    <tr>
                                    <td>
                                         @if($value->request=='AR')
                                         <span style="font-size:10px;width: 50px;">{{$a}}</span>
                                        @elseif($value->request=='CR')
                                        <span style="font-size:10px;width: 50px;">>{{ $b}}</span>
                                        @else
                                        <span style="font-size:10px;width: 50px;">{{ $c}}</span>
                                        @endif
                                        </td>
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->id }}</span></td>
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->docname }}</span></td>
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->docno }}</span></td>
                                       
                                       
                                        @if($value->request=='CR')
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span  style="font-size:10px;width: 50px;">{{ $value->nrevnumber}}</span></td>
                                        @endif
                                                   
                                        @if($value->request!='CR')
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span  style="font-size:10px;width: 50px;">{{ $value->erevnumber}}</span></td>
                                        @endif
                                                   
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->department }}</span></td> 
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->reqdate}}</span></td> 
                                        <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->approvedate }}</span></td> 
                                    </tr>
                               
                            </tbody>
                        @endforeach
                        
                    </table>
                    @endif



<script type="text/javascript">
var tab_text
function fnCovertTable(type)
            {
        if(document.getElementById('tabselected').value=='a1'){
        exptable="headerTable1";
        pdfvalue="tvalue2";
        x=3;
        }
        else if(document.getElementById('tabselected').value=='a2'){
        exptable="headerTable2";
        pdfvalue="tvalue1";
        x=4;
        }
        else if(document.getElementById('tabselected').value=='a3'){
        exptable="headerTable3";
        pdfvalue="tvalue3";
        x=0;
        }

        tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange; var j=0;var i=0;
        tab = document.getElementById(exptable);// id of table
        for(j = 0 ; j < tab.rows.length ; j++) 
            {    
                 tab_text=tab_text+"<tr>"
                 for(i=0;i<tab.rows[j].cells.length-x;i++){
                 tab_text=tab_text+"<td>"
                 tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
            }
            tab_text=tab_text+"</tr>";
            }
           
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            if(type=='excel')
                {
                    tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange; var j=0;var i=0;
        tab = document.getElementById(exptable);// id of table
        for(j = 0 ; j < tab.rows.length ; j++) 
            {    
                 tab_text=tab_text+"<tr>"
                 tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            }
           
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE "); 
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write(tab_text);
                    txtArea1.document.close();
                    txtArea1.focus(); 
                    sa=txtArea1.document.execCommand("SaveAs",true,"Say Thanks to Sumit.xls");
                }  
            else                 //other browser not tested on IE 11
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));  
            return (sa);
            }
            else if(type=='word')
            {
                var ua = window.navigator.userAgent;
                var msie = ua.indexOf("MSIE "); 
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
                    {
                        txtArea1.document.open("txt/html","replace");
                        txtArea1.document.write(tab_text);
                        txtArea1.document.close();
                        txtArea1.focus(); 
                        sa=div.innerHTML.document.execCommand("SaveAs",true,"Setilas.doc");
                    }  
                else             //other browser not tested on IE 11
                    sa = window.open('data:application/msword,' + encodeURIComponent(tab_text));  
                          
                return (sa);
            }
            else if(type=='print')
            {
                var oldPage=document.body.innerHTML;
                document.body.innerHTML=tab_text;
                window.print();
                document.body.innerHTML=oldPage; 
            }
            else if(type=="screenshot")
            {
                oldpage=document.body.innerHTML;
                document.body.innerHTML=tab_text;
                document.body.innerHTML=document.body.innerHTML+"<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
            }
            else if(type=="pdf")
            {
                tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange; var j=0;var i=0;
        tab = document.getElementById(exptable);// id of table
        tab_text=tab_text+"<tr>"
        for(i=0;i<tab.rows[0].cells.length-x;i++)
        {
            tab_text=tab_text+"<th>"
                 tab_text=tab_text+tab.rows[0].cells[i].innerHTML+"</th>";
        }
        tab_text=tab_text+"</tr>"
        for(j = 1 ; j < tab.rows.length ; j++) 
            {   tab_text=tab_text+"<tr>" 
                for(i=0;i<tab.rows[j].cells.length-x;i++)
                {
                 tab_text=tab_text+"<td>"
                 tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
                }
                 tab_text=tab_text+"</tr>";
            }
           
        tab_text=tab_text+"</table>";
   
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    
       
        document.getElementById(pdfvalue).value=tab_text;
        return true;
            }
            else if(type=='magnify')
            {
                tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
                var textRange; var j=0;var i=0;
                tab = document.getElementById('mainTable');// id of table
                for(j = 0 ; j < tab.rows.length ; j++) 
                    {    
                         tab_text=tab_text+"<tr>"
                         tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
                    }
                   
                tab_text=tab_text+"</table>";
                tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
                tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
                tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
             oldpage=document.body.innerHTML;
             var div1=document.createElement('div');
             div1.setAttribute("id","div1");
             var div2=document.createElement('div');
             div2.setAttribute("id","div2");
             div1.innerHTML=tab_text;
             div2.innerHTML="<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>"
             document.body.innerHTML=''
             document.body.appendChild(div1);
             document.body.appendChild(div2);
             
            }
        }
</script>