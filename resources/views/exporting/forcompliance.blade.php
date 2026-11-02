@if( count($data) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable1">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Compliance ID</span></span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Regulatory<br> Authority</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Legislative<br> Issue</th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Version/<br>Year/<br>Dated</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Act or<br> Regulation</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Requirement</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Related Aspect/<br>Risk Number</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Compliance<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process Owner</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process Stage</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Due Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Person Responsible for Requirement/Control</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Person Responsible for Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Revert reason</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Postedby</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    @foreach($data as $value)

    <tbody>
        <tr>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auth_id }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->authority }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->issue }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->version }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->regulation }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->requirement }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{date('M/d/Y',strtotime($value->duedate))}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->aspect }}</span></td>
            @if ($value->comp_status == 'Under Review')
            <td class="text-center " style="background-color:white;font-size:11px;width: 50px;color:black"><span style="background-color:#2887ed;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Complied")
            <td class="text-center " style="background-color:#40ff00;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Not Complied")
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:#f7533e;font-size:11px;width: 50px;color:white">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Partially Complied")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 50px;color:black"><span style="background-color:#ffec70;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->pro_status}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->duedate}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->person_control}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->person_monitor}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->reason}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->postedby }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->created_at }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->updatedat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->approvedby }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->closedby }}</span></td>

        </tr>

    </tbody>
    @endforeach
</table>
@endif

@if( count($data1) > 0)
<?php //dd($data); ?>
<table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter " id="headerTable2">
    <thead>
        <tr>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Compliance ID</span></span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Regulatory<br> Authority</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Legislative<br> Issue</th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Version/<br>Year/<br>Dated</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Act or<br> Regulation</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Requirement</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Target Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Related Aspect/<br>Risk Number</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Compliance<br>Status</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process Owner</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Process Stage</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Due Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Person Responsible for Requirement/Control</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Person Responsible for Monitoring</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Revert reason</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Postedby</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Added Date</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Last Reviewed</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Approved By</span></th>
            <th class="text-center" style="font-size:11px;width: 50px;text-transform:capitalize"><span style="font-size:11px;text-transform:capitalize">Issue Closed By</span></th>

        </tr>

    </thead>
    <?php $no=0;
                                         $today = Date("Y-m-d");
                                        ?>
    @foreach($data as $value)

    <tbody>
        <tr>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->auth_id }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->authority }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->issue }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->version }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->regulation }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->requirement )?></span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{date('M/d/Y',strtotime($value->duedate))}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;"><?php echo nl2br($value->aspect )?></span></td>

            @if ($value->comp_status == 'Under Review')
            <td class="text-center " style="background-color:white;font-size:11px;width: 50px;color:black"><span style="background-color:#2887ed;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Complied")
            <td class="text-center " style="background-color:#40ff00;font-size:11px;width: 50px;color:black"><span style="background-color:#baeb34;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Not Complied")
            <td class="text-center " style="background-color:red;font-size:11px;width: 50px;color:white"><span style="background-color:#f7533e;font-size:11px;width: 50px;color:white">{{ $value->comp_status }}</span></td>
            @elseif ($value->comp_status == "Partially Complied")
            <td class="text-center " style="background-color:yellow;font-size:11px;width: 50px;color:black"><span style="background-color:#ffec70;font-size:11px;width: 50px;color:black">{{ $value->comp_status }}</span></td>
            @endif
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->dep ? $value->dep->departmentname : '-' }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->pro_status }}</span></td>

            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->duedate}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->person_control}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->person_monitor}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{$value->reason}}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->postedby }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->created_at }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->updatedat }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->approvedby }}</span></td>
            <td class="text-center " style="font-size:11px;width: 50px;"><span style="font-size:11px;width: 50px;">{{ $value->closedby }}</span></td>

        </tr>

    </tbody>
    @endforeach
</table>
@endif

<script type="text/javascript">
    var tab_text

    function fnCovertTable(type) {

        //to check which anchor tag is selected based on that table is selected for exporting
        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
        } else if (document.getElementById('tabselected').value == 'a2') {
            exptable = "headerTable2";
            pdfvalue = "tvalue2";
        }

        tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length - 10; i++) {
                tab_text = tab_text + "<td>"
                tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
            }
            tab_text = tab_text + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
        tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        if (type == 'excel') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById(exptable); // id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "Say Thanks to Sumit.xls");
            } else //other browser not tested on IE 11
                sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
            return (sa);
        } else if (type == 'word') {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = div.innerHTML.document.execCommand("SaveAs", true, "Setilas.doc");
            } else //other browser not tested on IE 11
                sa = window.open('data:application/msword,' + encodeURIComponent(tab_text));

            return (sa);
        } else if (type == 'print') {
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            window.print();
            document.body.innerHTML = oldPage;
        } else if (type == "screenshot") {
            oldpage = document.body.innerHTML;
            document.body.innerHTML = tab_text;
            document.body.innerHTML = document.body.innerHTML + "<br/><button value='10' style='float:right'  class='btn btn-success' onclick='saveme()' id='save' download='file-name.png'>Save</button><span></span><button style='float:right' class='btn btn-success' onclick='back()'>Back</button><a id='blank'></a>";
        } else if (type == "pdf") {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById(exptable); // id of table
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[0].cells.length - 10; i++) {
                tab_text = tab_text + "<th>"
                tab_text = tab_text + tab.rows[0].cells[i].innerHTML + "</th>";
            }
            tab_text = tab_text + "</tr>"
            for (j = 1; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                for (i = 0; i < tab.rows[j].cells.length - 10; i++) {
                    tab_text = tab_text + "<td>"
                    tab_text = tab_text + tab.rows[j].cells[i].innerHTML + "</td>";
                }
                tab_text = tab_text + "</tr>";
            }

            tab_text = tab_text + "</table>";

            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params


            document.getElementById(pdfvalue).value = tab_text;
            return true;

        } else if (type == 'magnify') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            var j = 0;
            var i = 0;
            tab = document.getElementById('mainTable'); // id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, ""); //remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
            oldpage = document.body.innerHTML;
            var div1 = document.createElement('div');
            div1.setAttribute("id", "div1");
            var div2 = document.createElement('div');
            div2.setAttribute("id", "div2");
            div1.innerHTML = tab_text;
            div2.innerHTML = "<button type='button' class='btn btn-success'  onclick='back()'>Reset</button>"
            document.body.innerHTML = ''
            document.body.appendChild(div1);
            document.body.appendChild(div2);

        }
    }

</script>
