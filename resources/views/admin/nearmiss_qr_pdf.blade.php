
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:13px;
            color:#333;
            margin:25px;
        }

        h2{
            text-align:center;
            color:#252945;
            margin-bottom:8px;
        }

        .subtitle{
            text-align:center;
            margin-bottom:25px;
            color:#666;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        .qr-table td{
            text-align:center;
        }

        .qr-image{
            width:220px;
            height:220px;
        }

        .url{
            margin-top:10px;
            font-size:11px;
            color:#444;
        }

        .instruction-table{
            margin-top:35px;
            border:1px solid #CCCCCC;
        }

        .instruction-header{
            background:#252945;
            color:#FFFFFF;
            font-weight:bold;
            padding:8px;
            font-size:14px;
        }

        .instruction-body{
            padding:12px;
        }

        ul{
            margin:0;
            padding-left:20px;
        }

        li{
            margin-bottom:8px;
            line-height:18px;
        }

    </style>

</head>

<body>

<h2>Guest Near Miss Reporting</h2>

<div class="subtitle">
    Scan the QR Code below to access the Guest Near Miss Reporting form.
</div>

<table class="qr-table">

    <tr>
        <td>
            <img src="{{ $image }}" class="qr-image">
        </td>
    </tr>

    <tr>
        <td class="url">
            {{ $url }}
        </td>
    </tr>

</table>

<table class="instruction-table">

    <tr>

        <td class="instruction-header">

            Instructions

        </td>

    </tr>

    <tr>

        <td class="instruction-body">

            <ul>

                <li>Scan the QR code using any smartphone camera.</li>

                <li>Complete the Guest Near Miss Reporting form.</li>

                <li>Submit the report.</li>

                <li>Your report will be sent directly to the Safety Team.</li>

            </ul>

        </td>

    </tr>

</table>

</body>

</html>