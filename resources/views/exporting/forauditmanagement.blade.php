@if( count($data) > 0)
                            <?php //dd($data);
                            // $str = nl2br("First line\nSecond Line\nThird Line\n4th Line");

                            //echo $str; ?>
                            <table  class="table table-responsive table-bordered table-striped table-vcenter "  id="headerTable1" style="display:none">
                               <thead>
                                    <tr>

                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize"><b>Audit ID</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Department</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Auditors</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Auditee</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Quarter</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>From Date</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>To Date</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Process Stage</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Scope of Audit</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Time</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Focus</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Budgeted Hours</b></span></th>
                                        <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"><b>Added At</b></span></th>
                                    </tr>

                                </thead>
                                @foreach($data as $value)
                                <?php
							  $rr=$value->Aid;

                       $fiveYearsAgo = date("Y", strtotime("-5 years"));
                       ?>
                                                                    @if($value->status=="Planned")
                                                                    <?php $color1="yellow";?>
                                                                    @elseif($value->status=="Accepted")
                                                                    <?php $color1="orange";?>
                                                                    @elseif($value->status=="Rejected")
                                                                    <?php $color1="red";?>
                                                                    @elseif($value->status=="Completed")
                                                                    <?php $color1="#90edfb";?>
                                                                    @else
                                                                    <?php $color1="#a6ff00";?>
                                                                    @endif

                         <tbody>
                             <tr>

                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->aud_id }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auditors }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->contact}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->quarter}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->date}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->enddate}}</span></td>
                                <td class="text-center "style="background-color:{{$color1}};font-size:11px;width: 50px;"><span style="background-color:{{$color1}};font-size:11px;width: 50px;">{{ $value->status}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->scope}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->time}}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->focus }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->budgeted }}</span></td>
                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->created_at}}</span></td>
                                </tr>
                                </tbody>
                            @endforeach

                        </table>
                        @endif

<!--auditor list-->
@if( count($data1) > 0)
                            <?php //dd($data); ?>
                            <table  style="display:none;" id="headerTable2" class="table table-responsive table-bordered table-striped table-vcenter " >
                               <thead>
                                    <tr>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditors ID</span></th>
                                        <th class="text-center" style="font-size:11px;width: 250px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditors Name</span></th>
                                        <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Experience</span></th>
                                        <th class="text-center" style="font-size:11px;width: 350px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize"> Certificate</span></th>
                                    </tr>

                                </thead>
                                @foreach($data1 as $value)

                                    <tbody>
                                        <tr>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->id }}</span></td>
                                            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auditorsname }}</span></td>
                                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->experience }}</span></td>
                                          <td>
                                            @foreach($data2 as $dat) <?php //dd($data2); ?>
                                            @if( $dat->id==$value->id)
                                            <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                                <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $dat->certificate}}</span></td>
                                            </tr>
                                    @endif
                                @endforeach
                                </td>
                                        </tr>

                                </tbody>
                            @endforeach
                        </table>
@endif

<!--Audit Log-->
@if( count($data3) > 0)
                        <?php //dd($data); ?>
                        <table  class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable3" style="display:none;">
                           <thead>
                                <tr>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize">Audit ID</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Department</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Date</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process Stage</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Observations</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;background-color: orange;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;background-color: orange;"> High</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px; background-color: #FFFF00;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;background-color: #FFFF00;">Moderate</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;background-color: #00FF00;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;background-color: #00FF00;">Low</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">NCR Status</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Scope of Audit</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditors</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditee</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">To Date</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Time</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Focus</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Budgeted Hours</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Quarter</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Added At</span></th>
                                </tr>

                            </thead>
                            @foreach($data3 as $value)
                            <?php

                            $rr=$value->Aid;
                            ?>

                     <tbody>
                         <tr>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->aud_id }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->date}}</span></td>
                            @if($value->status=="Completed")
                                            <td class="text-center "style="background-color:#90edfb;font-size:11px;width: 100px;color:black"><span style="background-color:#90edfb;font-size:11px;width: 100px;color:black">{{$value->status }}</span></td>
                                            @else
                                            <td class="text-center "style="background-color:#a6ff00;font-size:11px;width: 100px;color:black"><span style="background-color:#a6ff00;font-size:11px;width: 100px;color:black">{{$value->status }}</span></td>
                                            @endif
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->observations}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->high}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;" ><span style="font-size:11px;width: 50px;">{{ $value->moderate}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->low}}</span></td>
                            @if($value->ncrstatus=='Open')
                                            <td class="text-danger "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:red;">{{ $value->ncrstatus}}</td>
                                            @endif
                                            @if($value->ncrstatus=='Closed')
                                            <td class="text-success "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:green;">{{ $value->ncrstatus}}</td>
                            @endif
                            @if($value->ncrstatus!='Closed'&& $value->ncrstatus!='Open')
                                            <td class="text-center "style="font-size:11px;width: 100px;"><span style="font-size:11px;width: 100px;color:black;">{{ $value->ncrstatus}}</td>
                            @endif
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->scope)?></span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auditors }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->contact }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->enddate}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->time}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->focus)?></span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->budgeted}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->quarter}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->created_at}}</span></td>
                                    </tr>

                            </tbody>
                        @endforeach

                    </table>
                    @endif

<!--closed auditors-->
@if( count($data4) > 0)
                        <?php //dd($data); ?>
                        <table  class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable4" style="display:none;">
                           <thead>
                                <tr>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize" ><span style="font-size:11px;text-transform:capitalize">Audit ID</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Department</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditors</span></th>
                                    <th class="text-center" style="font-size:11px;width: 200px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Auditee</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">From Date</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">To Date</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Process Stage</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Scope of Audit</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Time</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Focus</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Budgeted Hours</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Quarter</span></th>
                                    <th class="text-center" style="font-size:11px;width: 100px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize;">Added At</span></th>

                                </tr>

                            </thead>
                            @foreach($data4 as $value)
                            <?php

                            $rr=$value->Aid;
                            ?>

                     <tbody>
                         <tr>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->aud_id }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->department }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auditors}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->contact }}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->date}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->enddate}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;background-color:#a6ff00;" ><span style="font-size:11px;width: 50px;background-color:#a6ff00;">{{ $value->status}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->scope)?></span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->time}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->focus)?></span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->budgeted}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->quarter}}</span></td>
                            <td class="text-center "style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->created_at}}</span></td>

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
        x=5;
        }
        else if(document.getElementById('tabselected').value=='a2'){
        exptable="headerTable2";
        pdfvalue="tvalue2";
        x=0;
        }
        else if(document.getElementById('tabselected').value=='a3'){
        exptable="headerTable3";
        pdfvalue="tvalue3";
        x=9;
        }
        else if(document.getElementById('tabselected').value=='a4'){
        exptable="headerTable4";
        pdfvalue="tvalue4";
        x=6;
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
