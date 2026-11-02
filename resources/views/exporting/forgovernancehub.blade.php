@if(isset($datas) && count($datas)>0)
    @php
        $thInlineStyles = 'font-size:11px;width: 150px;text-transform:capitalize';
        $thSpanInlineStyles = 'font-size:10px;text-transform:capitalize';
    @endphp
    <input type="hidden" value="a1" id="tabselected">
    <table style="display:none;" class="table table-responsive table-bordered table-striped table-vcenter table1"
           id="headerTable1">
        <thead>
        <tr>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>#</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Document #</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Document Name</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Type</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Revision #</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Dated</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Owner</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Reviews</b></span></th>
            <th class="text-center" style="{{ $thInlineStyles }}"><span style="{{ $thSpanInlineStyles }}"><b>Views</b></span></th>
        </tr>
        </thead>
        @php $no = 0; @endphp
        @foreach($datas as $data)
            @php
                $owner = \App\Departments::where('id',$data->owner)->first();
                $average = App\Http\Controllers\DocumentController::getreviewsaverage($data->id);
                $percent = App\Http\Controllers\DocumentController::getreviewspercent($average);
            @endphp
            <tbody>
            <tr>
                <!-- # -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $no=$no+1}}</span></td>
                <!-- Document number -->
                <td class="text-center" style="font-size:11px;width: 50px;color:#0033cc"><span
                            style="font-size:10px;width: 50px;color:#0033cc">{{ $data->document_number }}</span></td>
                <!-- Document name -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{$data->document_name}}</td>
                <!-- Document type -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">
                    @if($data->type=="work_instuctions")
                            Work Instructions
                    @else
                        {{ ucwords(str_replace("_", " ", $data->type)) }}
                    @endif
                    </span>
                </td>
                <!-- Revision -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $data->revision }}</span></td>
                <!-- Dated -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{date('d-m-Y',strtotime($data->date))}}</span></td>
                <!-- Owner -->
                @if(isset($owner))
                    <td class="text-center" style="font-size:11px;width: 50px;"><span
                                style="font-size:10px;width: 50px;">{{ ucfirst($owner->departmentname) }}</span></td>
                @else
                    <td class="text-center" style="font-size:11px;width: 50px;"><span
                                style="font-size:10px;width: 50px;">-</span></td>
                @endif
                <!-- Reviews -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $average }}</span></td>
                <!-- Views -->
                <td class="text-center" style="font-size:11px;width: 50px;"><span
                            style="font-size:10px;width: 50px;">{{ $data->views }}</span></td>
            </tr>
            </tbody>
        @endforeach
    </table>
@endif

<script type="text/javascript">
    let tab_text;
    let tab;
    function fnCovertTable(type) {
        let exptable, pdfvalue;
        if (document.getElementById('tabselected').value == 'a1') {
            exptable = "headerTable1";
            pdfvalue = "tvalue1";
        } else if (document.getElementById('tabselected').value == 'a2') {
            exptable = "headerTable2";
            pdfvalue = "tvalue2";
        } else if (document.getElementById('tabselected').value == 'a3') {
            exptable = "headerTable3";
            pdfvalue = "tvalue3";
        } else if (document.getElementById('tabselected').value == 'a4') {
            exptable = "headerTable4";
            pdfvalue = "tvalue4";
        }
        tab_text = "<style>.text-center{ text-align: center }</style><table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
        let j = 0;
        let i = 0;
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
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params
        if (type == 'excel') {
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
        } else if (type == 'word') {
            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");
            const orientation = document.getElementById('orientation').value;
            const paper = document.getElementById('paper').value;
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
            document.getElementById(pdfvalue).value = tab_text;
            return true;
        } else if (type == 'magnify') {
            tab_text = "<table border='1' style='border-collapse:collapse;' bordercolor='black'  align='center' >";
            var textRange;
            j = 0;
            i = 0;
            tab = document.getElementById('mainTable');// id of table
            for (j = 0; j < tab.rows.length; j++) {
                tab_text = tab_text + "<tr>"
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
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