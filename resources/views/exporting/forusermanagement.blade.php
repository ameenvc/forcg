@php
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- Hidden input for tab selection -->
<input type="hidden" value="a1" id="tabselected">

<!-- PDF Export Form -->
<form method="post" action="{{url('/user/convert/file/tvalue1')}}" id="pdfForm" style="display:none;">
    @csrf
    <input type="hidden" id="tvalue1" name="tvalue1" />
    <input type="hidden" name="paper" value="a4" />
    <input type="hidden" name="orientation" value="landscape" />
</form>

@if( count($users) > 0)
    <table class="table table-responsive table-bordered table-striped table-vcenter" id="headerTable1" style="display:none">
        <thead>
            <tr>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">EMP ID</th>
                <!-- <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">Profile Picture</th> -->
                <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">Name</th>
                <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Email</th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">Department</th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">User Access</th>
                <th class="text-center" style="font-size:12px;width: 120px;text-transform:capitalize">Registered On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td class="text-center" style="font-size:12px;">{{ $u->employeeid ?? 'Not Set' }}</td>
                <!-- <td class="text-center" style="font-size:12px; vertical-align: middle;">
                    @if($u->profile_picture && file_exists(public_path('/storage/profile/'. $u->profile_picture)))
                        @php
                            $imagePath = public_path('storage/profile/'. $u->profile_picture);
                            $imageData = base64_encode(file_get_contents($imagePath));
                            $imageExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                            $mimeType = 'image/' . ($imageExtension === 'jpg' ? 'jpeg' : $imageExtension);
                            $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                        @endphp
                        <img src="{{ $imageSrc }}" class="avatar" alt="{{ $u->name }}" style="width: 30px; height: 30px; border-radius: 50%; display: block; margin: 0 auto;">
                    @else
                        @php
                            $imagePath = public_path('images/avatar10.jpg');
                            $imageData = base64_encode(file_get_contents($imagePath));
                            $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                        @endphp
                        <img src="{{ $imageSrc }}" class="avatar" alt="{{ $u->name }}" style="width: 30px; height: 30px; border-radius: 50%; display: block; margin: 0 auto;">
                    @endif
                </td> -->
                <td class="text-center" style="font-size:12px;">{{ $u->name ?? 'Not Set' }}</td>
                <td class="text-center" style="font-size:12px;">{{ $u->email ?? 'Not Set' }}</td>
                <td class="text-center" style="font-size:12px;">{{ $u->deptid == NULL ? 'Not Set' : $u->deptid }}</td>
                <td class="text-center" style="font-size:12px;">{{ $u->type == 1 ? 'Admin' : 'User' }}</td>
                <td class="text-center" style="font-size:12px;">{{ $u->created_at ? $u->created_at->format('d-m-Y') : 'Not Set' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif

@php
    $deletedUsers = \App\User::onlyTrashed()->orderBy('name', 'asc')->get();
@endphp

@if(count($deletedUsers) > 0)
<table class="table table-responsive table-bordered table-striped table-vcenter" id="headerTable2" style="display:none">
    <thead>
        <tr>
            <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">EMP ID</th>
            <!-- <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">Profile Picture</th> -->
            <th class="text-center" style="font-size:12px;width: 150px;text-transform:capitalize">Name</th>
            <th class="text-center" style="font-size:12px;width: 200px;text-transform:capitalize">Email</th>
                <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">Department</th>
            <th class="text-center" style="font-size:12px;width: 100px;text-transform:capitalize">User Access</th>
            <th class="text-center" style="font-size:12px;width: 120px;text-transform:capitalize">Deleted On</th>
        </tr>
    </thead>
    <tbody>
        @foreach($deletedUsers as $u)
        <tr>
            <td class="text-center" style="font-size:12px;">{{ $u->employeeid ?? 'Not Set' }}</td>
            <!-- <td class="text-center" style="font-size:12px; vertical-align: middle;">
                @if($u->profile_picture && file_exists(public_path('/storage/profile/'. $u->profile_picture)))
                    @php
                        $imagePath = public_path('storage/profile/'. $u->profile_picture);
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $imageExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                        $mimeType = 'image/' . ($imageExtension === 'jpg' ? 'jpeg' : $imageExtension);
                        $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                    @endphp
                    <img src="{{ $imageSrc }}" class="avatar" alt="{{ $u->name }}" style="width: 30px; height: 30px; border-radius: 50%; display: block; margin: 0 auto;">
                @else
                    @php
                        $imagePath = public_path('images/avatar10.jpg');
                        $imageData = base64_encode(file_get_contents($imagePath));
                        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
                    @endphp
                    <img src="{{ $imageSrc }}" class="avatar" alt="{{ $u->name }}" style="width: 30px; height: 30px; border-radius: 50%; display: block; margin: 0 auto;">
                @endif
            </td> -->
            <td class="text-center" style="font-size:12px;">{{ $u->name ?? 'Not Set' }}</td>
            <td class="text-center" style="font-size:12px;">{{ $u->email ?? 'Not Set' }}</td>
            <td class="text-center" style="font-size:12px;">{{ $u->deptid == NULL ? 'Not Set' : $u->deptid }}</td>
            <td class="text-center" style="font-size:12px;">{{ $u->type == 1 ? 'Admin' : 'User' }}</td>
            <td class="text-center" style="font-size:12px;">{{ $u->deleted_at ? $u->deleted_at->format('d-m-Y') : 'Not Set' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif

<script type="text/javascript">
var tab_text

function exportUsers(format) {
    // Get current tab
    let currentTab = $('#userTabs .nav-link.active').attr('href');
    let isDeletedUsers = currentTab === '#deleted-users';

    // Set the tab selected value for export
    if (isDeletedUsers) {
        document.getElementById('tabselected').value = 'a2';
    } else {
        document.getElementById('tabselected').value = 'a1';
    }

    // Call the conversion function
    fnCovertTable(format);
}

function fnCovertTable(type) {
    if(document.getElementById('tabselected').value=='a1'){
        exptable="headerTable1";
    }
    else if(document.getElementById('tabselected').value=='a2'){
        exptable="headerTable2";
    }

    tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black' align='center'>";
    var textRange; var j=0;var i=0;
    tab = document.getElementById(exptable);// id of table
    for(j = 0 ; j < tab.rows.length ; j++) {
        tab_text=tab_text+"<tr>"
        for(i=0;i<tab.rows[j].cells.length;i++){
            tab_text=tab_text+"<td>"
            tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
        }
        tab_text=tab_text+"</tr>";
    }
    tab_text=tab_text+"</table>";
    tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
    // tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
    tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // removes input params

    if(type=='excel') {
        tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black' align='center'>";
        var textRange; var j=0;var i=0;
        tab = document.getElementById(exptable);// id of table
        tab_text=tab_text+"<tr>"
        for(i=0;i<tab.rows[0].cells.length;i++) {
            tab_text=tab_text+"<th>"
            tab_text=tab_text+tab.rows[0].cells[i].innerHTML+"</th>";
        }
        tab_text=tab_text+"</tr>"
        for(j = 1 ; j < tab.rows.length ; j++) {
            tab_text=tab_text+"<tr>"
            for(i=0;i<tab.rows[j].cells.length;i++) {
                tab_text=tab_text+"<td>"
                tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
            }
            tab_text=tab_text+"</tr>";
        }
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        // tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // removes input params

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {      // If Internet Explorer
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=txtArea1.document.execCommand("SaveAs",true,"Users_Export.xls");
        } else {                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        }
        return (sa);
    }
    else if(type=='word') {
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");
        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {      // If Internet Explorer
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=div.innerHTML.document.execCommand("SaveAs",true,"Users_Export.doc");
        } else {             //other browser not tested on IE 11
            sa = window.open('data:application/msword,' + encodeURIComponent(tab_text));
        }
        return (sa);
    }
    else if(type=='print') {
        var oldPage=document.body.innerHTML;
        document.body.innerHTML=tab_text;
        window.print();
        document.body.innerHTML=oldPage;
    }
    else if(type=='pdf') {
        tab_text="<table border='1' style='border-collapse:collapse;' bordercolor='black' align='center'>";
        var textRange; var j=0;var i=0;
        tab = document.getElementById(exptable);// id of table
        tab_text=tab_text+"<tr>"
        for(i=0;i<tab.rows[0].cells.length;i++) {
            tab_text=tab_text+"<th>"
            tab_text=tab_text+tab.rows[0].cells[i].innerHTML+"</th>";
        }
        tab_text=tab_text+"</tr>"
        for(j = 1 ; j < tab.rows.length ; j++) {
            tab_text=tab_text+"<tr>"
            for(i=0;i<tab.rows[j].cells.length;i++) {
                tab_text=tab_text+"<td>"
                tab_text=tab_text+tab.rows[j].cells[i].innerHTML+"</td>";
            }
            tab_text=tab_text+"</tr>";
        }
        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        // tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // removes input params

        // Set the table content to hidden input and submit form
        document.getElementById('tvalue1').value=tab_text;
        document.getElementById('pdfForm').submit();
        return true;
    }
}
</script>
