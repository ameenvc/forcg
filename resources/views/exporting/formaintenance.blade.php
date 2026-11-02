@if( count($data) > 0)
                            <?php //dd($data);
                            // $str = nl2br("First line\nSecond Line\nThird Line\n4th Line");

                            //echo $str; ?>
                            <table  class="table table-responsive table-bordered table-striped table-vcenter "  id="headerTable1" style="display:none">
                               <thead>
                                    <tr>
                                     
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize"><b>S.No</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment&nbspID</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Department</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment Type</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Location/area</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Serial number</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Make/model</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Testing frequency</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Preventive maintenance frequency</b></span></th>
                                        
                                    </tr>
                                   
                                </thead>
                                <?php $no=0;?>
                                    @foreach($data as $value)
                                    <?php $rr=$value->id;?>
	
                         <tbody>
                             <tr>
                    
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$no=$no+1}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->equid }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->type}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->location}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->serial}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->make}}</span></td>
                              <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->testfreq}}</span></td> 
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->freq}}</span></td> 
                                </tr>        
                                </tbody>
                            @endforeach
                            
                        </table>
                        @endif


@if( count($data1) > 0)
                            <?php //dd($data); ?>
                            <table  style="display:none;" id="headerTable2" class="table table-responsive table-bordered table-striped table-vcenter " >
                               <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize"><b>S.No</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment&nbspID</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Department</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment Type</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Location/area</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Serial number</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Make/model</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Preventive maintenance frequency</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Status</b></span></th>
                                        </tr>
                                    
                                </thead>
                                <?php $no=0;?>
                                @foreach($data1 as $value)
                                <?php $rr=$value->id;?>
                                
                                    <tbody>
                                        <tr>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$no=$no+1}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->equid }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->type}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->location}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->serial}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->make}}</span></td>
                           
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->freq}}</span></td> 
                                @if($value->pmstatus=='PM Done')
                                <td class="text-success "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:green;">{{ $value->pmstatus}}</td>  
                                    @elseif($value->pmstatus=='PM Due')
                               <td class="text-danger "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:red;">{{ $value->pmstatus}}</td>
                                            
                                @elseif($value->pmstatus=='Time for PM')
                                
                                <td class="text-warning "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:#FFFF00;">{{ $value->pmstatus}}</td>
                               
                                @endif
                            </tr>
                                      	
                                </tbody>
                            @endforeach
                        </table>
@endif 

<!--test plan-->
@if( count($data2) > 0)
                        <?php //dd($data); ?>
                        <table  class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable3" style="display:none;">
                           <thead>
                                <tr>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize"><b>S.No</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment&nbspID</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Department</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment Type</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Location/area</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Serial number</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Make/model</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Testing frequency</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Status</b></span></th>
                                        </tr>
                               
                            </thead>
                            <?php $no=0;?>
                                    @foreach($data2 as $value)
                                    <?php $rr=$value->id;?>
                     <tbody>
                         <tr>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$no=$no+1}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->equid }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->type}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->location}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->serial}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->make}}</span></td>
                       
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->testfreq}}</span></td>
                            @if($value->teststatus=='Test Passed')
                            <td class="text-success "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:green;">{{ $value->teststatus}}</td>  
                                @elseif($value->teststatus=='Test Due')
                           <td class="text-danger "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:red;">{{ $value->teststatus}}</td>
                            @elseif($value->teststatus=='Test Failed')
                            <td class="text-info "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:#90edfb;">{{ $value->teststatus}}</td>
                              
                            @elseif($value->teststatus=='Time for Test')
                            
                            <td class="text-warning "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:#FFFF00;">{{ $value->teststatus}}</td>
                           
                            @endif
                            </tr>
                               
                            </tbody>
                        @endforeach
                        
                    </table>
                    @endif

<!--breakdown-->
@if( count($data3) > 0)
                        <?php //dd($data); ?>
                        <table  class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable4" style="display:none;">
                           <thead>
                                <tr>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize"><b>S.No</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment&nbspID</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Department</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Equipment Type</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Location/area</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Serial number</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Make/model</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Severity</b></span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Priority</b></span></th>
                                    
                                </tr>
                               
                            </thead>
                            <?php $no=0;?>
                                    @foreach($data3 as $value)
                                    <?php $rr=$value->id;?>

                     <tbody>
                         <tr>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$no=$no+1}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->equid }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->type}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->location}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->serial}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->make}}</span></td>
                          <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->severity}}</span></td> 
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->priority}}</span></td> 
                                
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
        pdfvalue="tvalue1";
        x=0;
        }
        else if(document.getElementById('tabselected').value=='a2'){
        exptable="headerTable2";
        pdfvalue="tvalue2";
        x=0;
        }
        else if(document.getElementById('tabselected').value=='a3'){
        exptable="headerTable3";
        pdfvalue="tvalue3";
        x=0;
        }
        else if(document.getElementById('tabselected').value=='a4'){
        exptable="headerTable4";
        pdfvalue="tvalue4";
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
