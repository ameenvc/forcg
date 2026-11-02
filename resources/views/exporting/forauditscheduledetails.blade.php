
@if(count($data) > 0)                           
    <table class="table table-responsive table-bordered table-striped "  id="headerTable1" style="display:none">
        <thead>
            <tr>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                    <span style="font-size:12px;text-transform:capitalize"><b>Sl No</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                    <span style="font-size:12px;text-transform:capitalize"><b>Audit ID</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                    <span style="font-size:12px;text-transform:capitalize"><b>Department</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>Auditors</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>Quarter</b></span>
                </th>   
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>From Date</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>To Date</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>Auditee</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>Process Stage</b></span>
                </th>
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">
                <span style="font-size:12px;text-transform:capitalize"><b>Audit Status</b></span>
                </th>           
            </tr>
        </thead>
        <tbody>
            @php $no = 0; @endphp
            @foreach($data as $value)
                <tr>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                    <span style="font-size:12px;width: 50px;">{{ $no=$no+1}}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                        <span style="font-size:12px;width: 50px; text-align:center;">{{ $value->aud_id }}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                       <span style="font-size:12px;width: 50px;text-transform:capitalize;">{{ $value->departmentObj ? $value->departmentObj->departmentname : '' }}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                       <span style="font-size:12px;width: 50px;text-transform:capitalize;">{{ $value->auditors }}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                       <span style="font-size:12px;width: 50px;">{{ $value->quarter}}</span>
                    </td>
                                     
                    <td class="text-center" style="font-size:12px;width: 50px;">
                       <span style="font-size:12px;width: 50px;">{{date('d-m-Y',strtotime($value->date))}}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;" >
                        <span style="font-size:12px;width: 50px;">{{date('d-m-Y',strtotime($value->enddate))}}</span>
                        <!-- {{ \Carbon\Carbon::parse($value->enddate)->format('d-m-Y') }} -->
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                       <span style="font-size:12px;width: 50px;text-transform:capitalize;">{{ $value->contact}}</span>
                    </td>
                    <td class="text-center" style="font-size:12px;width: 50px;">
                        @if($value->status=='Accepted')
                        <span style="font-size:12px;width: 50px;color: #176044;background-color: #e0edcf;border-color: #bff0dd;">
                            {{$value->status}}
                        </span>
                        @elseif($value->status=='Completed')
                        <span style="font-size:12px;width: 50px;color: #25499c;background-color: #d9e2f6;border-color: #d9e2f6;">
                            {{$value->status}}
                        </span>
                        @elseif($value->status=='Rejected')
                        <span style="font-size:12px;width: 50px;color: #af1e1e;background-color: #f8d4d4;border-color: #fbeaea;">
                            {{$value->status}}
                        </span>
                        @elseif($value->status=='Planned')
                        <span style="font-size:12px;width: 50px;color: #af6f1e;background-color: #fbdece;border-color: #fbf4ea;">
                            {{$value->status}}
                        </span>
                        @else
                        <span style="font-size:12px;width: 50px;color: #25499c;background-color: #d9e2f6;border-color: #d9e2f6;">
                            {{$value->status}}
                        </span>
                        @endif
                    </td>
                    @php
                         $all_checklist_percentage = DB::table('audit_checklist_new_records')->where('audit_schedule_id', $value->id)->pluck('completed_percentage')->toArray();
                         $average_percent = $all_checklist_percentage ? round(array_sum($all_checklist_percentage) / count($all_checklist_percentage), 2) : 0;
                    @endphp
                    <td class="text-center" style="font-size:12px;width: 50px;" >
                        <span style="font-size:12px;width: 50px;text-align:center;">{{$average_percent}}%</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script type="text/javascript">
var tab_text
function fnCovertTable(type)
    {
        if(document.getElementById('tabselected').value=='a1'){
            exptable="headerTable1";
            pdfvalue="tvalue1";
        }
        tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable);// id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length; i++) {
                tab_text = tab_text + "<td class='text-center'>"
                tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
            }
            tab_text = tab_text + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
    if(type=='excel'){
        tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            j = 0;
            i = 0;
            tab = document.getElementById(exptable);// id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                // If Internet Explorer
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
                return (sa);
            }  else {
                //other browser not tested on IE 11
                // Create a Blob with the Excel MIME type
                const blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                const url = URL.createObjectURL(blob);
                const currentUsername = document.getElementById('current_user_name').value

                // Open in a new tab or prompt download
                const link = document.createElement("a");
                link.href = url;
                link.download = "excelsavedby_" + currentUsername + ".xls"; // Specify the file name
                link.click();
                URL.revokeObjectURL(url); // Clean up
            }
    }
    else if(type=='word'){
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            const orientation = 'landscape';
            const paper = 'a3';
            const currentUsername = document.getElementById('current_user_name').value
            const html = "<style>@page portrait_A4_page{size:595.3pt 841.9pt;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.4pt;mso-footer-margin:35.4pt;mso-paper-source:0;} " +
                " div.portrait_A4_page { page:portrait_A4_page;} " +
                " @page landscape_A4_page {size:841.9pt 595.3pt;mso-page-orientation:landscape;margin:72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin:35.45pt;mso-footer-margin:35.45pt;mso-paper-source:0;} " +
                " div.landscape_A4_page { page:landscape_A4_page;} " +
                " @page portrait_A3_page {size: 841.9pt 1190.55pt;mso-page-orientation: portrait;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.portrait_A3_page { page:portrait_A3_page;} " +
                " @page landscape_A3_page {size: 1190.55pt 841.9pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.landscape_A3_page { page:landscape_A3_page;} " +
                " @page portrait_A2_page {size: 1190.55pt 1683.78pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.portrait_A2_page { page:portrait_A2_page;} " +
                " @page landscape_A2_page {size: 1683.78pt 1190.55pt;mso-page-orientation: landscape;margin: 72.0pt 72.0pt 72.0pt 72.0pt;mso-header-margin: 35.45pt;mso-footer-margin: 35.45pt;mso-paper-source: 0;}" +
                " div.landscape_A2_page { page:landscape_A2_page;} " +
                " .text-center{ align-content: center } " +
                "</style><div class=" + (orientation === "portrait" ? "portrait_" + (paper === "a2" ? "A2" : (paper === "a3" ? "A3": "A4")) + "_page" : "landscape_" + (paper === "a2" ? "A2" : (paper === "a3" ? "A3": "A4")) + "_page") + ">" + tab_text + "</div>"
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                // If Internet Explorer
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = div.innerHTML.document.execCommand("SaveAs", true, "Setilas.doc");
                return (sa);
            }  else {
                const source = 'data:application/msword,' + encodeURIComponent(html)
                var fileDownload = document.createElement("a");
                document.body.appendChild(fileDownload);
                fileDownload.href = source;
                fileDownload.download = "wordsavedby_" + currentUsername + ".doc"
                fileDownload.click();
                document.body.removeChild(fileDownload);
            }
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
