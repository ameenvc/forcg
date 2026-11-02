<div id="exportSection" class="d-none">
    @if(count($data1)>0)
        <table class="table table-responsive table-bordered table-striped table-vcenter table2" id="headerTable1">
            @foreach($data1 as $value)
                <thead>
                    <tr>
                        <th colspan="10" style="font-size:13px;
                                text-transform:capitalize;
                                background:#f0f0f0;
                                text-align:left;
                                padding-left:8px;
                                font-weight:bold;">
                            <span style="font-weight:bold;font-size:15px;">Activity:</span>
                            <span style="font-size:13px;color:gray;">{{ $value->activity }}</span>
                        </th>
                    </tr>
                    <tr style="background:#e9ecef;">
                        <th class="text-center">S.No</th>
                        <th class="text-center">OHS Risk ID</th>
                        <th class="text-center">Hazard</th>
                        <th class="text-center">Original Risk</th>
                        <th class="text-center">Residual Risk</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Type of Risk</th>
                        <th class="text-center">R/NR/EM</th>
                        <th class="text-center">Monitoring</th>
                        <th class="text-center">Process Stage</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no=1; @endphp
                    @foreach($data2 as $dat)
                        @if($dat->ohs_id==$value->id)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td class="text-center">{{ $value->department }}{{ $value->count }}/{{ $dat->riskid }}</td>
                                <td class="text-center"><em class="text-muted">{{ $dat->hazards }}</em></td>
                                <td class="text-center excelrow">
                                    @if( $dat->OrR > 0 && $dat->OrR < 4 ) 
                                    <span class="green" style="background-color: #28a745; color: #fff; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->OrR }}</span>
                                    @elseif( $dat->OrR > 3 && $dat->OrR < 7 )
                                    <span class="orange" style="background-color: #ffc107; color: #000; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->OrR }}</span>   
                                    @elseif( $dat->OrR > 7 )
                                    <span class="red" style="background-color: #dc3545; color: #fff; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->OrR }}</span>
                                    @endif
                                </td>
                                <td class="text-center excelrow">
                                    @if( $dat->RR > 0 && $dat->RR < 4 ) 
                                    <span class="green" style="background-color: #28a745; color: #fff; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->RR }}</span>
                                    @elseif( $dat->RR > 3 && $dat->RR < 7 )
                                    <span class="orange" style="background-color: #ffc107; color: #000; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->RR }}</span>   
                                    @elseif( $dat->RR > 7 )
                                    <span class="red" style="background-color: #dc3545; color: #fff; padding: 2px 5px; border-radius: 3px;">R:{{ $dat->RR }}</span>
                                    @endif
                                </td>
                                @php 
                                    $category = '';
                                    if( $dat->RR > 0 && $dat->RR < 4 ) 
                                        $category = 'Low';
                                    elseif( $dat->RR > 3 && $dat->RR < 7 )
                                        $category = 'Medium';
                                    elseif( $dat->RR > 7 )
                                        $category = 'High';
                                @endphp
                                <td class="text-center"><em class="text-muted">{{$category}}</em></td>
                                <td class="text-center"><em class="text-muted">{{ $dat->type }}</em></td>
                                <td class="text-center"><em class="text-muted">{{ $dat->R_NR_EM }}</em></td>
                                <td class="text-center"><em class="text-muted">{{ $dat->monitor }}</em></td>
                                <td class="text-center">
                                    @if($dat->risk_status=="Rejected")
                                    <span class="red" style="background-color: #dc3545; color: #000; padding: 2px 5px; border-radius: 3px;">{{ $dat->risk_status }}</span>
                                    @elseif($dat->risk_status=="Review Pending")
                                    <span class="orange" style="background-color: #ffc107; color: #000; padding: 2px 5px; border-radius: 3px;">{{ $dat->risk_status }}</span>
                                    @else
                                    <span class="green" style="background-color: #28a745; color: #000; padding: 2px 5px; border-radius: 3px;">{{ $dat->risk_status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            @endforeach
        </table>
    @endif
</div>
<script type="text/javascript">
    function fnCovertTable(type) {
        let pdfvalue;
        let current_user_name = document.getElementById('current_user_name').value;
        if (type == "excel") {
            const section = document.getElementById("exportSection");
            const filename = `ohs_risk_${current_user_name}.xls`;
            const html = `
                <html xmlns:x="urn:schemas-microsoft-com:office:excel">
                <head>
                    <meta charset="utf-8">
                    <style>
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
                        th[colspan] { text-align: left; background: #f2f2f2; font-weight: bold; }
                        thead th { background: #d9d9d9; font-weight: bold; }
                        .red {color:#dc3545 !important;}
                        .orange {color:#ffc107 !important;}
                        .green {color:#28a745 !important;}
                    </style>
                </head>
                <body>${section.innerHTML}</body>
                </html>
            `;
            const blob = new Blob([html], { type: "application/vnd.ms-excel" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = filename;
            a.click();
            URL.revokeObjectURL(url);
            return false;
        }else if (type == "word") {
            const section = document.getElementById("exportSection");
            const filename = `ohs_risk_${current_user_name}.doc`;
            const html = `
                <html xmlns:w="urn:schemas-microsoft-com:office:word">
                <head>
                    <meta charset="utf-8">
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 11px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
                        th[colspan] { background: #f2f2f2; font-weight: bold; text-align: left; }
                        thead th { background: #d9d9d9; }
                    </style>
                </head>
                <body style="margin:0 !important;">${section.innerHTML}</body>
                </html>
            `;

            const blob = new Blob([html], { type: "application/msword" });
            const url = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = filename;
            a.click();
            URL.revokeObjectURL(url);
            return false;
        }else if (type == 'print') {
            const section = document.getElementById("exportSection");
            // Prepare HTML for printing
            const printContents = `
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Activity Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
                        thead th { background: #d9d9d9; font-weight: bold; }
                        th[colspan] {
                            text-align: left !important;
                            background: #f2f2f2;
                            font-weight: bold;
                            font-size: 11px;
                            padding-left: 8px;
                        }
                        @media print {
                            body { margin: 10mm; }
                            table { page-break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    ${section.innerHTML}
                </body>
                </html>
            `;
            // Open new window for printing
            const printWindow = window.open('', '', 'height=800,width=1000');
            printWindow.document.open();
            printWindow.document.write(printContents);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();

            return false;
        }else if (type == "pdf") {
            const pdfvalue = "tvalue1";
            const section = document.getElementById("exportSection");
            const styledHTML = `
                <html>
                <head>
                    <meta charset="utf-8">
                    <style>
                        body { font-family: Arial, sans-serif; font-size: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
                        th, td { border: 1px solid #000; padding: 6px; text-align: center; vertical-align: middle; }
                        thead th { background: #f8f8f8; font-weight: bold; }
                        th[colspan] { text-align: left; font-weight: bold; font-size: 11px; background: #f2f2f2; }
                        .text-muted { color: #6c757d; font-style: italic; }
                        em { font-style: italic; color: gray; }
                    </style>
                </head>
                <body>${section.innerHTML}</body>
                </html>
            `;
            document.getElementById(pdfvalue).value = styledHTML;
            return true;
        }
    }
</script>