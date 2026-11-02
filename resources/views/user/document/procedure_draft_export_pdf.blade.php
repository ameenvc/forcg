<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Draft Procedures</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        @page {
            margin: 15mm 10mm 5mm 10mm;
            @if(isset($contentAnalysis))
                size: {{ $contentAnalysis['paper_size'] }} {{ $contentAnalysis['orientation'] }};
            @else
                size: A4 landscape;
            @endif
        }

        body {
            margin: 0;
            padding: 0;
            font-size: 16px;
        }
        
        /* Standard font size for all document text */
        * {
            font-size: 16px !important;
        }

        .content-block {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .section-break {
            page-break-before: auto;
            break-before: auto;
        }

        .force-page-break {
            page-break-before: always !important;
            break-before: always !important;
            height: 0 !important;
            border: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /* Table pagination rules optimized for dompdf */
        .rich_text_container table {
            page-break-inside: auto !important;
            page-break-after: auto !important;
            width: 100%;
        }
        
        .rich_text_container table thead {
            display: table-header-group;
        }
        
        .rich_text_container table tbody {
            page-break-inside: auto !important;
            page-break-before: auto !important;
        }
        
        .rich_text_container table tr {
            page-break-inside: avoid;
            page-break-after: auto;
            orphans: 2;
            widows: 2;
        }
        
        .rich_text_container table td,
        .rich_text_container table th {
            page-break-inside: avoid;
        }

        .avatar-thumb {
            height: 8rem;
            max-height: 8rem;
        }
        .avatar-thumb img {
            object-fit: contain;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
        }

        .main_heading{
            color: #3366CC;
            word-wrap: break-word;
            text-transform: uppercase;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        footer .pagenum:before {
            content: 'under draft';
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 10mm;
            right: 10mm;
            height: 3mm;
        }

        .document-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1px solid #CCCCCC !important;
        }

        .document-table img {
            height: auto !important;
            max-width: 100% !important;
        }

        .document-table td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            vertical-align: middle;
            padding: 0 8px;
        }

        .document-table td.responsibility-content {
            vertical-align: middle;
        }

        /* Section header rows - keep together */
        .document-table tr.section-break {
            page-break-inside: avoid;
            break-inside: avoid;
            page-break-after: avoid;
            break-after: avoid;
        }

        /* Content rows - ALLOW breaking for long content */
        .document-table tr.responsibility-row {
            page-break-inside: auto;
            break-inside: auto;
        }

        /* Default tr behavior - allow breaks */
        .document-table tr {
            page-break-inside: auto;
            break-inside: auto;
        }


        /* Orphans and widows control - keep at least 2 lines together */
        .document-table td {
            orphans: 2;
            widows: 2;
        }

        /* Prefer to break AFTER content, not in the middle */
        .document-table tr {
            page-break-after: auto;
        }

        .rich_text_container {
            font-size: 13px !important;
            line-height: 1.4 !important;
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
            max-width: 100%;
            margin-bottom: 1mm !important;
            orphans: 3;
            widows: 3;
        }

        .rich_text_container * {
            max-width: 100% !important;
            box-sizing: border-box !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
        }

        .rich_text_container h1,
        .rich_text_container h2 {
            font-weight: bold !important;
            color: #333333 !important;
            line-height: 1.3 !important;
        }

        .rich_text_container h3 {
            font-weight: bold !important;
            color: #444444 !important;
            line-height: 1.3 !important;
        }

        .rich_text_container h4,
        .rich_text_container h5,
        .rich_text_container h6 {
            font-weight: bold !important;
            color: #555555 !important;
            line-height: 1.3 !important;
        }

        .rich_text_container p {
            color: #333333 !important;
            margin-bottom: 0;
        }

        .rich_text_container strong,
        .rich_text_container b {
            font-weight: bold !important;
        }

        .rich_text_container em,
        .rich_text_container i {
            font-style: italic !important;
        }

        .rich_text_container ul {
            margin: 8px 0 !important;
            padding-left: 20px !important;
        }

        .rich_text_container ol {
            margin: 8px 0 !important;
            padding-left: 20px !important;
        }

        .rich_text_container li {
            margin: 4px 0 !important;
            line-height: 1.4 !important;
        }

        .rich_text_container a {
            color: #0066cc !important;
            text-decoration: underline !important;
        }

        .rich_text_container div,
        .rich_text_container span {
            word-break: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        .rich_text_container table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 2px 0 !important;
            table-layout: fixed !important;
            page-break-inside: auto;
            break-inside: auto;
            border: 1px solid #CCCCCC !important;
        }

        .rich_text_container table td,
        .rich_text_container table th {
            padding: 3px !important;
            vertical-align: middle !important;
            word-wrap: break-word !important;
            overflow-wrap: break-word !important;
            line-height: 1.3 !important;
            max-width: none !important;
            border: 1px solid #ddd !important;
            hyphens: auto;
        }

        .rich_text_container table.long-content {
        }

        .rich_text_container table.long-content td,
        .rich_text_container table.long-content th {
            padding: 2px !important;
            line-height: 1.2 !important;
        }

        .rich_text_container table td[style*="background-color: rgb(142, 124, 195)"] {
            background-color: #8e7cc3 !important;
            color: white !important;
            font-weight: bold !important;
            text-align: center !important;
        }

        .rich_text_container table td[style*="background-color: rgb(217, 210, 233)"] {
            background-color: grey !important;
            font-weight: bold !important;
            text-align: center !important;
        }

        .rich_text_container table td[style*="background-color: rgb(239, 239, 239)"] {
            background-color: #f0f0f0 !important;
        }

        .rich_text_container table td[style*="color: rgb(183, 183, 183)"] {
            color: #b7b7b7 !important;
        }

        .rich_text_container table colgroup,
        .rich_text_container table col {
            display: none !important;
        }

        .rich_text_container table div[style*="max-height"] {
            max-height: none !important;
            height: auto !important;
        }

        .rich_text_container table td {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        .rich_text_container table[style*="width: 0px"] {
            width: 100% !important;
        }

        .rich_text_container img {
            max-width: 100% !important;
            height: 100px !important;
            width: auto !important;
            display: block !important;
        }

        .rich_text_container .video-thumbnail {
            max-width: 100% !important;
            margin: 20px 0 !important;
            page-break-inside: avoid;
            break-inside: avoid;
            min-height: 200px !important;
        }

        .rich_text_container .video-thumbnail img {
            max-width: 100% !important;
            width: 100% !important;
            height: auto !important;
        }

        .rich_text_container div[style*="border:3px solid"] {
            min-height: 220px !important;
            margin: 20px 0 !important;
        }

        .rich_text_container div[style*="width:100%;max-width:480px"] {
            min-width: 300px !important;
            width: 100% !important;
        }

        .long-content-section {
            page-break-inside: auto;
            break-inside: auto;
        }

        .content-overflow {
            line-height: 1.3 !important;
        }

        .responsibility-row {
            margin-bottom: 5px;
        }

        .responsibility-content {
            orphans: 2;
            widows: 2;
        }
    </style>
</head>
<body>
<footer>
    <div class="pagenum-container text-center" style="color:red;">
        <span class="pagenum"></span>
    </div>
</footer>

<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom:10px;" width="100%">
    <tr>
        <td width="30%">&nbsp;</td>
        <td width="40%">&nbsp;</td>
        <td width="30%" style="text-align: right">
            <span><b>{{ $document->document_number }}</b></span>
        </td>
    </tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered document-table">
    <thead>
    <tr>
        <td class="text-center main_heading" colspan="5">
            <b>{{$document_name}}</b>
        </td>
    </tr>
    </thead>
    <tbody>
    @php $i = 0; $n = 1; @endphp
    @foreach ($document_titles as $document_title)
        @php
            $isSplitRow = isset($document_title->_is_split) && $document_title->_is_split;
            $splitPart = isset($document_title->_split_part) ? $document_title->_split_part : 1;
            $splitTotal = isset($document_title->_split_total) ? $document_title->_split_total : 1;
            
            if (isset($document_title->_single_rich_text)) {
                $contents = collect([$document_title->_single_rich_text]);
                $contentCount = 1;
            } else {
                $contents = $document_title->contentId;
                $contentCount = count($contents);
            }
        @endphp

        @php
            $hideHeader = isset($document_title->_hide_section_header) && $document_title->_hide_section_header;
            
            // Increment section number BEFORE showing a new section header (except for the first section)
            if (!$hideHeader && $i > 0) {
                $n++;
            }
        @endphp
        @if (!$hideHeader && (!$isSplitRow || $splitPart == 1))
        <tr class="table-active section-break">
            <td class="text-center" style="width:8%;border-right: 1px solid #D3D3D3;vertical-align: middle">
                <span style="vertical-align: middle"><b><?php printf("%.1f", $n); ?></b></span>
            </td>
            <td class="text-left" style="width:17%;border-right: 1px solid #D3D3D3;vertical-align: middle">
                <span style="vertical-align: middle"><b>RESPONSIBILITY</b></span>
            </td>
            <td class="text-left" style="width: 75%;vertical-align: middle">
                <span style="vertical-align: middle"><b>{{ucfirst(trim($document_title->title))}}</b></span>
            </td>
        </tr>
        @endif

        @php 
            // Only reset $m when showing a NEW section header, otherwise continue from previous $m
            if (!$hideHeader) {
                $m = 1;  // New section, start at 1
            }
            // If hiding header, $m continues from previous value
        @endphp
        @foreach($contents as $index => $con)
            <tr class="responsibility-row">
                <td class="text-center merged-cell-col1" style="vertical-align: top; {{ $isSplitRow ? ($splitPart == 1 ? 'border-bottom-color: white;' : 'border-top-color: white; border-bottom-color: white;') : '' }}">
                    @if (!$isSplitRow || $splitPart == 1)
                        <span style="vertical-align: middle">
                            @php echo $n . '.' . $m; @endphp
                        </span>
                    @else
                        <span>&nbsp;</span>
                    @endif
                </td>
                <td class="text-left merged-cell-col2" style="vertical-align: top; {{ $isSplitRow ? ($splitPart == 1 ? 'border-bottom-color: white;' : 'border-top-color: white; border-bottom-color: white;') : '' }}">
                    @if (!$isSplitRow || $splitPart == 1)
                        @if(isset($con->responsibility))
                            <span style="word-wrap:break-word;vertical-align: middle">{{ucfirst(trim($con->responsibility))}}</span>
                        @else
                            <span>-</span>
                        @endif
                    @else
                        <span>&nbsp;</span>
                    @endif
                </td>
                <td class="text-left responsibility-content" style="vertical-align: middle; {{ $isSplitRow ? ($splitPart == 1 ? 'border-bottom-color: white;' : 'border-top-color: white; border-bottom-color: white;') : '' }}">
                    <div class="rich_text_container">
                        @if(isset($con->rich_text))
                            @php
                                $currentPaperSize = isset($contentAnalysis) ? $contentAnalysis['paper_size'] : 'A4';
                                $contentRichText = \App\Helpers\VideoThumbnailHandler::processVideoContent(trim($con->rich_text), $currentPaperSize);
                            @endphp
                            <span style="font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;word-wrap:break-word;">{!! $contentRichText !!}</span>
                            
                            @if ($isSplitRow && isset($document_title->_split_index) && isset($document_title->_split_total))
                                <div style="color:#999;margin-top:5px;font-style:italic;">
                                    (Continued - Part {{ $document_title->_split_index + 1 }} of {{ $document_title->_split_total }})
                                </div>
                            @endif
                        @else
                            <span>-</span>
                        @endif
                    </div>
                </td>
                <td style="width: 1px; border:none;">&nbsp;</td>
                <td style="width: 1px; border:none;">&nbsp;</td>
            </tr>
            @php
                // Only increment $m for the last part of a split, or for non-split items
                // This prevents split chunks from counting as multiple items
                if (!$isSplitRow || $splitPart == $splitTotal) {
                    $m++;
                }
            @endphp
        @endforeach
        
        @php
            // Only increment $i when we showed a section header (to track number of sections shown)
            if (!$hideHeader) {
                $i++;
            }
        @endphp
    @endforeach
    </tbody>
</table>
</body>
</html>