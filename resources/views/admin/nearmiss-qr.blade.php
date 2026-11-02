@extends('admin.nav')
@section('content')
<!-- Page Content -->
<div class="content">
    <!-- Dynamic Table Full -->

    @if(Session::has('message'))
    <div class="alert alert-primary">
        <a class="close" data-dismiss="alert">×</a>
        {!!Session::get('message')!!}
    </div>
    @endif
    <div class="block block-rounded shadow-sm">
        <div class="block-header">
            <h3 class="block-title">
                MS.18.01 QR Code Management
            </h3>

            <nav class="flex-sm-00-auto ml-sm-3">
                <ol class="breadcrumb breadcrumb-alt mb-0">
                    <li class="breadcrumb-item">
                        Near Miss
                    </li>
                </ol>
            </nav>
        </div>

        <div class="block-content text-center pb-5">

            <h4 class="mb-2 font-weight-bold">
                Guest Near Miss Reporting
            </h4>

            <p class="text-muted">
                Scan this QR code to access the Guest Near Miss Reporting form.
            </p>

            <div class="card mx-auto shadow-sm"
                style="width:340px;border-radius:15px;">

                <div class="card-body">

                    <div id="qr-code">
                        {!! $qrCode !!}
                    </div>

                    <hr>

                    <small class="text-muted">
                        {{ url('/public/nearmiss/report') }}
                    </small>

                </div>

            </div>

            <div class="mt-4">
                <button class="btn btn-success" id="downloadImage">
                    <i class="fa fa-image"></i>
                    Download PNG
                </button>
                <a href="{{ route('admin.qr.download.pdf') }}"
                class="btn btn-danger">

                    <i class="fa fa-file-pdf mr-1"></i>

                    Download PDF

                </a>

            </div>

            <div class="alert alert-info mt-4 text-left mx-auto"
                style="max-width:700px;">

                <strong>Instructions</strong>

                <ul class="mb-0 mt-2">

                    <li>Scan the QR code using any smartphone camera.</li>

                    <li>Complete the Guest Near Miss Reporting form.</li>

                    <li>Submit the report.</li>

                    <li>Your report will be sent directly to the Safety Team.</li>

                </ul>

            </div>

        </div>
    </div>
</div>
<script src="https://unpkg.com/html-to-image@1.11.11/dist/html-to-image.js"></script>
<script>
    document.getElementById("downloadImage").addEventListener("click", function () {
        const node = document.getElementById("qr-code");
        htmlToImage.toPng(node, {
            cacheBust: true,
            pixelRatio: 4,
            backgroundColor: "#ffffff"
        })
        .then(function (dataUrl) {
            const link = document.createElement("a");
            link.download = "Guest-Near-Miss-QR.png";
            link.href = dataUrl;
            link.click();
        })
        .catch(function (error) {
            console.error(error);
            alert("Unable to generate image.");
        });
    });
</script>
@endsection