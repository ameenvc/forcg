@if( count($data) > 0)
<?php //dd($data); ?>
<table class="table table-responsive table-bordered table-striped table-vcenter " style="display:none;" id="headerTable1">
    <thead>
        <tr>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">VendorID</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Vendor Name</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Purpose of Addition</span></th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Year Of Establishment</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Vendor Type</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Frequency of Evaluation</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Status</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Approve Date</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Scope of work/product/service</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Address</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Location</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Contact</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Certified ISO Standards</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Basis Of Approval</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Trial Order Made</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Outcome of Trial Order</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Added By</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Added Date</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Last Re-evaluated By</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Last Re-evaluated At</span></th>
            <th class="text-center" style="font-size:12px;width: 50px;text-transform:capitalize"><span style="font-size:10px;text-transform:capitalize">Percentage</span></th>
        </tr>

    </thead>
    <?php $no=0;?>
    <?php   $d=date("Y-m-d");
                                        $t=date("Y-m-d",strtotime("-14 days"));
                                ?>
    @foreach($data as $value)
    <?php $rr=$value->id; ?>
    <tbody>
        <tr>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">VEN{{ $value->id }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->vendor_name }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->vendor_group }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->Yoe }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->vendor_type }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->eval }}</span></td>
            @if($value->status=="Evaluated")
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:green">ReEvaluated</span></td>
            @elseif($value->status!="Evaluated" && ($q==$d || $value->status=="Due Evaluation") )
            <?php  echo App\Http\Controllers\VendorController::Dueevaluation($value->due_date); ?>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:red">Due Evaluation</span></td>
            @elseif($value->status!="Evaluated" && ($z==$d || $value->status=="Near To Evaluation" ) )
            <?php  echo App\Http\Controllers\VendorController::Neartoevaluate($value->due_date); ?>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:orange">Evaluation relevant date</span></td>
            @else
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;background-color:blue">Approved</span></td>
            @endif

            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->approved_date }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br( $value->scope)?></span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;"><?php echo nl2br( $value->address)?></span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->location}}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->contact }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;">
                <span style="font-size:10px;width: 50px;">
                    @php $i = 0; @endphp
                    @foreach($value->standards as $s)
                    {{ $s->name }}@if($i != count($value->standards) - 1),@endif
                    @php $i++; @endphp
                    @endforeach
                </span>
            </td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                    @if($value->vendor_group=="Existing")
                    {{ $value->basis_approval}}
                    @endif
                </span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->trial_order }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                    @if($value->trial_order=="Yes")
                    {{ $value->outcome }}
                    @endif
                </span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->added_by }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->created_at }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->evaluated_by }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">{{ $value->evaluated_at }}</span></td>
            <td class="text-center " style="font-size:12px;width: 50px;"><span style="font-size:10px;width: 50px;">
                    @if($value->per!==NULL )
                    {{ $value->per }}%
                    @endif
                </span></td>
        </tr>

    </tbody>
    @endforeach

</table>
@endif


<script type="text/javascript">
    var tab_text

    function fnCovertTable(type) {

        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
        }


        tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        var textRange;
        var j = 0;
        var i = 0;
        tab = document.getElementById(exptable); // id of table
        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + "<tr>"
            for (i = 0; i < tab.rows[j].cells.length - 14; i++) {
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
            for (i = 0; i < tab.rows[0].cells.length - 14; i++) {
                tab_text = tab_text + "<th>"
                tab_text = tab_text + tab.rows[0].cells[i].innerHTML + "</th>";
            }
            tab_text = tab_text + "</tr>"
            for (j = 1; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                for (i = 0; i < tab.rows[j].cells.length - 14; i++) {
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
