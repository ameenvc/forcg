@if(count($issues)>0) 
                        <?php //dd($issues); 
                        
                        ?>
                        <!--set table id as headerTable1-->
                        <table class="table table-responsive table-bordered table-striped table-vcenter "  id="headerTable1" style="display:none" >
                           <thead>
                            <tr >
                                <!-- Error number 118 -->
                                <!-- Added text-tranform : capitalize style below to make every letter of the word capital -->
                                
                                <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Title</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Type</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Issue</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Purpose</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Monitoring</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Bias</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Accountability</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Status</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Added date</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Last Reviewed</b></span></th>

                       </tr>
                               
                            </thead>
                            <?php $no=0;?>
                            @foreach($issues as $issue)
                            <?php 
                                            
                                            $rr=$issue->id;
                                           
                                            $today = Date("Y-m-d");
                                            $color = null ;?>
                           
                            <tbody>
                    <tr>        
                    <td style="font-size:11px;width: 50px;"><span class="text-center "style="font-size:10px;width: 50px;">{{$issue->title}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$issue->type}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->issue}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br( $issue->purpose)?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->monitoring}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->bias}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->accountability}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->status}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->created_at}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->updated_at}}</span></td>
                    </tr>
                        </tbody>
                    @endforeach
                @endif 

@if(count($issues2)>0) 
                        <?php //dd($issues); 
                        ?>
                        <!--set table id as headerTable1-->
                        <table class="table table-responsive table-bordered table-striped table-vcenter "  id="headerTable3" style="display:none" >
                           <thead>
                            <tr >
                                <!-- Error number 118 -->
                                <!-- Added text-tranform : capitalize style below to make every letter of the word capital -->
                                
                                <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>CR#</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Title</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Category</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Initiator</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Department</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Changing<br/> Product/Service</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Priority</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Status</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Scope</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Reason</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Benifits</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Impact</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Risk Identified</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Review Remarks</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Approved Remarks</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Posted By</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Reviewel Authority</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Approval Authority</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Added Date</b></span></th>
                                <th style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Reviewed Date</b></span></th>
                                <!-- <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>STATUS</b></span></th>
                                <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>ADDED DATE</b></span></th>
                                <th class="text-center" style="font-size:11px;width: 150px;text-transform:capitalize"><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>LAST REVIEWED</b></span></th> -->
                       </tr>
                               
                            </thead>
                            <?php $no=0;?>
                            @foreach($issues2 as $issue)
                            <?php 
                                            
                                            $rr=$issue->id;
                                           
                                            $today = Date("Y-m-d");
                                            $color = null ;?>
                           
                            <tbody>
                    <tr>        
                    <td style="font-size:11px;width: 50px;"><span class="text-center "style="font-size:10px;width: 50px;">{{$issue->crid}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$issue->title}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->category}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->initiator}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->department}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->changing_product}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->priority}}</span></td>
                    <td style="font-size:12px;width: 50px;">
                    @if($issue->status=="Review Pending")
                    <span   style="font-size:10px;width: 50px;background-color:grey;">
                    {{$issue->status}}
                    </span>
                    @elseif($issue->status=="Reviewed")
                    <span  style="font-size:10px;width: 50px;background-color:green;">
                    {{$issue->status}}
                    </span>
                    @elseif($issue->status=="Approved")
                    <span  style="font-size:10px;width: 50px;background-color:grey;">
                    {{$issue->status}}
                    </span>
                    @elseif($issue->status=="Rejected")
                    <span  style="font-size:10px;width: 50px;background-color:red;">
                    {{$issue->status}}
                    </span>
                    @else
                    <span  style="font-size:10px;width: 50px;background-color:orange;">
                    {{$issue->status}}
                    </span>
                    @endif
                    </td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->scope )?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->reason )?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->benefits )?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->impacts )?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br($issue->risk_identified )?></span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->remarks }}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->higherremarks }}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->postedby }}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->rev_authority}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->appr_authority}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->created_at))}}</span></td>
                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{date('M/d/Y',strtotime($issue->reviewed_at))}}</span></td>

                    </tr>
                        </tbody>
                    @endforeach
                @endif 


                @if(count($issues1))
                <table class="table table-responsive table-bordered table-striped table-vcenter "  id="headerTable2" style="display:none" >
                <thead>
                                    <tr>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Id</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Interested Party(IP)</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Type</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Purpose of IP</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Requirement of IP</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Monitoring Methodology</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Accountability</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Posted By</b></span></th>
                                         <th style="font-size:11px;width: 150px;text-transform:capitalize" ><span class="text-center" style="font-size:10px;text-transform:capitalize"><b>Added Date</b></span></th>
                                       
                                        <!-- <th class="text-center" style="font-size:10px;">View</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $no=0;?>
                                @foreach($issues1 as $issue) 
                                <?php $rr=$issue->Cid;                               
                                //giving unique id to each + button
                                $btnid=$issue->partyid."1";
                                $spnid=$issue->partyid."2";
                                ?>  
                                    <tr>
                                      
                                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{$issue->partyid}}</span></td>
                                    <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                                          {{$issue->intr_party}}
                                          </span>
                                        </td>
                                        <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                                          {{$issue->type}}
                                          </span>
                                        </td>
                                        <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                                           {{$issue->purpose}}
                                           </span>
                                        </td>
                                        <td ><span style="font-size:10px;width: 50px;"></span></td>
                                        <td ><span style="font-size:10px;width: 50px;"></span></td>
                                        <td ><span style="font-size:10px;width: 50px;"></span></td>                                     
                                        <td ><span style="font-size:10px;width: 50px;"></span></td>                                     
                                        <td ><span style="font-size:10px;width: 50px;"></span></td>                                     
                                      
                                        
                                      
                                    </tr>
                                   <tbody id="{{$issue->partyid}}">
                                  
                                        @foreach($issues3 as $dat)                                          
                                            @if( $dat->Cid == $issue->Cid) 
                                                <tr>
                                                    
                                                    <td ><span style="font-size:10px;width: 50px;"></span></td>
                                                    <td ><span style="font-size:10px;width: 50px;"></span></td>
                                                    <td ><span style="font-size:10px;width: 50px;"></span></td> 
                                                    <td ><span style="font-size:10px;width: 50px;"></span></td> 
                                                    
                                                     <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$dat->reqip}}</span></td>
                                                     <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$dat->monitoring}}</span></td>
                                                     <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$dat->accountability}}</span></td>
                                                     <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{$dat->posted_by}}</span></td>
                                                     <td style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"> {{date('M/d/Y',strtotime($dat->created_at))}}</span></td>
                                                    
                                                                                                                     
                                                </tr>                                                
                                            @endif  
                                        @endforeach                                                   
                                    </tbody>
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
        pdfvalue="tvalue1";
        x=3;
        }
        else if(document.getElementById('tabselected').value=='a2'){
        exptable="headerTable2";
        pdfvalue="tvalue2";
        x=0;
        }
        else if(document.getElementById('tabselected').value=='a3'){
        exptable="headerTable3";
        pdfvalue="tvalue3";
        x=12;
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
        return false;
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