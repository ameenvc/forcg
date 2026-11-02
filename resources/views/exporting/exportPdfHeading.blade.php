<div style="width:100%; margin-bottom:10px;">
    <table width="100%">
        <tr>
            <!-- Logo -->
            <td style="width:20%; text-align:left;">
                 @if($clientDetails->client_logo)
                    @php
                        $logoPath = public_path("storage/clientlogos/" . $clientDetails->client_logo);
                        $logoBase64 = null;

                        if (file_exists($logoPath)) {
                            try {
                                $imageData = file_get_contents($logoPath);
                                if ($imageData !== false) {
                                    $imageInfo = getimagesize($logoPath);
                                    $mimeType = $imageInfo['mime'] ?? 'image/png';
                                    $logoBase64 = "data:{$mimeType};base64," . base64_encode($imageData);
                                }
                            } catch (Exception $e) {
                                // Handle error silently
                            }
                        }
                    @endphp
                    @if($logoBase64)
                        <img id="imgPreview" src="{{ $logoBase64 }}"
                                alt="CompanyLogo"
                                style="max-width: 200px; max-height: 80px; object-fit: contain;">
                    {{-- @else
                        <div style="width: 200px; height: 80px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; color: #999;">
                            [Logo Not Found]
                        </div> --}}
                    @endif
                @endif
            </td>
            <!-- Company Name -->
            <td style="width:60%; text-align:center;">
                <span style="font-size:16px; font-weight:bold;">
                    {{ $clientDetails ? $clientDetails->company_name : '' }}
                </span>
            </td>

            <!-- Export Date -->
            <td style="width:20%; text-align:right;">
                <span style="font-size:12px;">
                    Exported: {{ date('d-m-Y') }}
                </span>
            </td>
        </tr>
    </table>
</div>
