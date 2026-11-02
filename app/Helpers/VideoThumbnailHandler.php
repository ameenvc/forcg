<?php

namespace App\Helpers;

/**
 * Advanced Video Thumbnail Handler for PDF Generation
 * Updated for DomPDF compatibility - uses only basic CSS properties
 */
class VideoThumbnailHandler
{
    /**
     * Process HTML content and replace videos with thumbnails
     * Also adjusts inline font sizes based on paper size
     */
    public static function processVideoContent($htmlContent, $paperSize = 'A4')
    {

        $htmlContent = self::adjustInlineFontSizes($htmlContent, $paperSize);

        $htmlContent = self::processYouTubeEmbeds($htmlContent);

        $htmlContent = self::processVimeoEmbeds($htmlContent);

        return self::processVideoTags($htmlContent);
    }

    /**
     * Adjust inline font sizes in HTML content based on paper size
     * Font sizes in database are already correct, so minimal adjustment
     * Settings are loaded from config/pdf_export.php
     * Also removes text-size-adjust properties that interfere with PDF rendering
     */
    public static function adjustInlineFontSizes($htmlContent, $paperSize = 'A4')
    {
        $multiplier = 1.0;
        
        if ($paperSize === 'A4') {
            $multiplier = config('pdf_export.font_scaling.A4', 1.1);
        } elseif ($paperSize === 'A3') {
            $multiplier = config('pdf_export.font_scaling.A3', 1.0);
        }
        
        if ($multiplier === 1.0) {
            return $htmlContent;
        }

        $htmlContent = preg_replace('/(-webkit-|-moz-|-ms-)?text-size-adjust\s*:\s*[^;]+;?/i', '', $htmlContent);

        $pattern = '/(font-size\s*:\s*)([0-9.]+)(pt|px|em|rem|%)/i';

        $htmlContent = preg_replace_callback($pattern, function($matches) use ($multiplier) {
            $property = $matches[1]; // "font-size:"
            $value = floatval($matches[2]); // numeric value
            $unit = $matches[3]; // "pt", "px", "em", etc.

            $newValue = round($value * $multiplier, 1);

            return $property . $newValue . $unit;
        }, $htmlContent);

        $increasePercent = round(($multiplier - 1) * 100);
        \Log::info("Adjusted inline font sizes for {$paperSize} paper (multiplier: {$multiplier}x = {$increasePercent}% increase, removed text-size-adjust properties)");

        return $htmlContent;
    }

    /**
     * Convert base64-encoded images to temporary files
     */
    public static function convertBase64ImagesToFiles($htmlContent)
    {
        $pattern = '/<img[^>]*src=["\']data:image\/(png|jpe?g|gif|webp);base64,([^"\']+)["\'][^>]*>/i';
        
        return preg_replace_callback($pattern, function($matches) {
            $imageType = strtolower($matches[1]);
            $base64Data = $matches[2];
            $fullMatch = $matches[0];
            
            try {
                $imageData = base64_decode($base64Data);
                
                if ($imageData === false || strlen($imageData) < 100) {
                    return $fullMatch;
                }
                
                $extension = ($imageType === 'jpg') ? 'jpeg' : $imageType;
                
                $tempDir = storage_path('app/temp');
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                
                $filename = 'pdf_img_' . md5($base64Data) . '.' . $extension;
                $filePath = $tempDir . '/' . $filename;
                
                if (!file_exists($filePath)) {
                    file_put_contents($filePath, $imageData);
                }
                
                preg_match('/style=["\']([^"\']*)["\']/', $fullMatch, $styleMatch);
                preg_match('/alt=["\']([^"\']*)["\']/', $fullMatch, $altMatch);
                preg_match('/class=["\']([^"\']*)["\']/', $fullMatch, $classMatch);
                
                $style = isset($styleMatch[1]) ? $styleMatch[1] : '';
                $alt = isset($altMatch[1]) ? $altMatch[1] : '';
                $class = isset($classMatch[1]) ? $classMatch[1] : '';

                $newImgTag = '<img src="' . $filePath . '"';
                if ($style) $newImgTag .= ' style="' . $style . '"';
                if ($alt) $newImgTag .= ' alt="' . htmlspecialchars($alt) . '"';
                if ($class) $newImgTag .= ' class="' . $class . '"';
                $newImgTag .= ' />';
                
                \Log::info('Converted base64 image to file', [
                    'original_size' => strlen($base64Data),
                    'file_path' => $filePath,
                    'image_type' => $imageType
                ]);
                
                return $newImgTag;
                
            } catch (\Exception $e) {
                \Log::error('Failed to convert base64 image: ' . $e->getMessage());
                // Return original on error
                return $fullMatch;
            }
        }, $htmlContent);
    }

    /**
     * Process YouTube iframe embeds
     */
    private static function processYouTubeEmbeds($content)
    {
        // Pattern for YouTube embed URLs
        $patterns = [
            // Standard YouTube embed
            '/<iframe[^>]*src=["\']([^"\']*youtube\.com\/embed\/([^"\'&?\/]+))[^"\']*["\'][^>]*>.*?<\/iframe>/is',
            // YouTube nocookie embed
            '/<iframe[^>]*src=["\']([^"\']*youtube-nocookie\.com\/embed\/([^"\'&?\/]+))[^"\']*["\'][^>]*>.*?<\/iframe>/is',
            // youtu.be short URLs
            '/<iframe[^>]*src=["\']([^"\']*youtu\.be\/([^"\'&?\/]+))[^"\']*["\'][^>]*>.*?<\/iframe>/is'
        ];

        foreach ($patterns as $pattern) {
            $content = preg_replace_callback($pattern, function($matches) {
                $videoId = $matches[2];
                return self::generateYouTubeThumbnailHTML($videoId, $matches[1]);
            }, $content);
        }

        return $content;
    }

    /**
     * Generate YouTube thumbnail HTML - DomPDF Compatible with Base64
     */
    private static function generateYouTubeThumbnailHTML($videoId, $originalUrl)
    {
        $videoUrl = "https://www.youtube.com/watch?v={$videoId}";

        $thumbnailData = self::getYouTubeThumbnailBase64WithFileCache($videoId);
        
        $decoded = base64_decode($thumbnailData);
        $isSvg = (strpos($decoded, '<svg') === 0);
        
        $mimeType = $isSvg ? 'image/svg+xml' : 'image/jpeg';

        return '<img src="data:' . $mimeType . ';base64,' . $thumbnailData . '" style="display:block;height:100px;width:auto;margin-top:10px;" alt="YouTube Video Thumbnail" />';
    }

    /**
     * Process Vimeo iframe embeds
     */
    private static function processVimeoEmbeds($content)
    {
        $patterns = [
            '/<iframe[^>]*src=["\']([^"\']*vimeo\.com\/video\/([^"\'&?\/]+))[^"\']*["\'][^>]*>.*?<\/iframe>/is',
            '/<iframe[^>]*src=["\']([^"\']*player\.vimeo\.com\/video\/([^"\'&?\/]+))[^"\']*["\'][^>]*>.*?<\/iframe>/is'
        ];

        foreach ($patterns as $pattern) {
            $content = preg_replace_callback($pattern, function($matches) {
                $videoId = $matches[2];
                return self::generateVimeoThumbnailHTML($videoId, $matches[1]);
            }, $content);
        }

        return $content;
    }

    /**
     * Generate Vimeo thumbnail HTML - DomPDF Compatible with Base64
     */
    private static function generateVimeoThumbnailHTML($videoId, $originalUrl)
    {
        $videoUrl = "https://vimeo.com/{$videoId}";

        $thumbnailData = self::getVimeoThumbnailBase64WithFileCache($videoId);
        
        $decoded = base64_decode($thumbnailData);
        $isSvg = (strpos($decoded, '<svg') === 0);
        
        $mimeType = $isSvg ? 'image/svg+xml' : 'image/jpeg';

        // PDF-friendly dimensions: use max-width/max-height with height:auto to prevent overflow
        return '<img src="data:' . $mimeType . ';base64,' . $thumbnailData . '" style="display:block;height:100px;width:auto;" alt="Vimeo Video Thumbnail" />';
    }

    /**
     * Process HTML5 video tags - DomPDF Compatible
     */
    private static function processVideoTags($content)
    {
        return preg_replace_callback(
            '/<video[^>]*>.*?<\/video>/is',
            function($matches) {
                return '
<table style="width:100%;margin:25px auto;border:5px solid #333333;border-collapse:separate;border-spacing:0;background-color:#ffffff;max-width:600px;">
    <tr>
        <td style="padding:25px;text-align:center;">
            <div style="margin-bottom:20px;">
                <div style="width:100%;max-width:500px;height:300px;background-color:#333333;margin:0 auto;text-align:center;padding-top:120px;color:white;font-size:80px;border:3px solid #ddd;">
                    ▶
                </div>
            </div>
            <div style="background-color:#333333;color:white;padding:15px;margin:15px auto;width:250px;text-align:center;font-size:20px;font-weight:bold;border-radius:8px;">
                🎬 HTML5 Video
            </div>
            <div style="background-color:#f8f9fa;padding:12px;margin:15px auto;font-size:14px;color:#333;border:2px solid #e9ecef;border-radius:6px;">
                Video content (not displayable in PDF)
            </div>
        </td>
    </tr>
</table>';
            },
            $content
        );
    }

    /**
     * Get YouTube thumbnail as base64 encoded string
     */
    public static function getYouTubeThumbnailBase64($videoId)
    {
        $thumbnailUrls = [
            "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
            "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
            "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
            "https://img.youtube.com/vi/{$videoId}/default.jpg"
        ];

        foreach ($thumbnailUrls as $url) {
            $base64 = self::fetchImageAsBase64($url);
            if ($base64) {
                return $base64;
            }
        }

        return null;
    }

    /**
     * Get Vimeo thumbnail as base64 encoded string
     */
    public static function getVimeoThumbnailBase64($videoId)
    {
        try {
            $oembedUrl = "https://vimeo.com/api/oembed.json?url=https://vimeo.com/{$videoId}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $oembedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $response) {
                $data = json_decode($response, true);
                if (isset($data['thumbnail_url'])) {
                    $thumbnailUrl = str_replace('_295x166', '_640x360', $data['thumbnail_url']);
                    $base64 = self::fetchImageAsBase64($thumbnailUrl);
                    if ($base64) {
                        return $base64;
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to fetch Vimeo thumbnail via API: " . $e->getMessage());
        }

        $directUrls = [
            "https://i.vimeocdn.com/video/{$videoId}_640x360.jpg",
            "https://i.vimeocdn.com/video/{$videoId}_295x166.jpg"
        ];

        foreach ($directUrls as $url) {
            $base64 = self::fetchImageAsBase64($url);
            if ($base64) {
                return $base64;
            }
        }

        return null;
    }

    /**
     * Fetch image from URL and convert to base64
     */
    private static function fetchImageAsBase64($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200 && $imageData && strlen($imageData) > 1000) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($imageData);

                if (strpos($mimeType, 'image/') === 0) {
                    return base64_encode($imageData);
                }
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to fetch thumbnail: " . $e->getMessage());
        }

        return null;
    }

    /**
     * Generate a fallback base64 image (SVG - no GD library needed)
     */
    public static function generateFallbackBase64Image()
    {
        $svg = <<<SVG
<svg width="500" height="300" xmlns="http://www.w3.org/2000/svg">
  <!-- Gray background -->
  <rect width="500" height="300" fill="#f0f0f0"/>
  
  <!-- Red circle (YouTube style) -->
  <circle cx="250" cy="150" r="60" fill="#FF0000"/>
  
  <!-- White play triangle -->
  <polygon points="230,125 230,175 275,150" fill="#FFFFFF"/>
  
  <!-- Optional: "Video Unavailable" text -->
  <text x="250" y="230" font-family="Arial, sans-serif" font-size="14" fill="#666666" text-anchor="middle">
    Video Thumbnail Unavailable
  </text>
</svg>
SVG;

        return base64_encode($svg);
    }

    /**
     * Generate a Vimeo-styled fallback base64 image (SVG - no GD library needed)
     */
    public static function generateVimeoFallbackBase64Image()
    {
        $svg = <<<SVG
<svg width="500" height="300" xmlns="http://www.w3.org/2000/svg">
  <!-- Vimeo blue background -->
  <rect width="500" height="300" fill="#1AB7EA"/>
  
  <!-- White circle -->
  <circle cx="250" cy="150" r="60" fill="#FFFFFF"/>
  
  <!-- Blue play triangle (Vimeo blue) -->
  <polygon points="230,125 230,175 275,150" fill="#1AB7EA"/>
  
  <!-- Optional: "Video Unavailable" text -->
  <text x="250" y="230" font-family="Arial, sans-serif" font-size="14" fill="#FFFFFF" text-anchor="middle">
    Video Thumbnail Unavailable
  </text>
</svg>
SVG;

        return base64_encode($svg);
    }

    /**
     * Alternative method using file system caching for YouTube
     */
    public static function getYouTubeThumbnailBase64WithFileCache($videoId)
    {
        $cacheDir = storage_path('app/thumbnail_cache');
        $cacheFile = $cacheDir . "/youtube_{$videoId}.txt";

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
            return file_get_contents($cacheFile);
        }

        $base64 = self::getYouTubeThumbnailBase64($videoId);

        if (!$base64) {
            $base64 = self::generateFallbackBase64Image();
        }

        if ($base64) {
            file_put_contents($cacheFile, $base64);
        }

        return $base64;
    }

    /**
     * Alternative method using file system caching for Vimeo
     */
    public static function getVimeoThumbnailBase64WithFileCache($videoId)
    {
        $cacheDir = storage_path('app/thumbnail_cache');
        $cacheFile = $cacheDir . "/vimeo_{$videoId}.txt";

        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 86400) {
            return file_get_contents($cacheFile);
        }

        $base64 = self::getVimeoThumbnailBase64($videoId);

        if (!$base64) {
            $base64 = self::generateVimeoFallbackBase64Image();
        }

        // Save to cache
        if ($base64) {
            file_put_contents($cacheFile, $base64);
        }

        return $base64;
    }

    /**
     * Clean up old temporary image files (older than 24 hours)
     * Call this periodically to prevent disk space issues
     */
    public static function cleanupOldTempFiles()
    {
        $tempDir = storage_path('app/temp');
        
        if (!is_dir($tempDir)) {
            return;
        }
        
        $files = glob($tempDir . '/pdf_img_*');
        $now = time();
        $deleted = 0;
        
        foreach ($files as $file) {
            if (is_file($file)) {
                // Delete files older than 24 hours
                if ($now - filemtime($file) >= 86400) {
                    unlink($file);
                    $deleted++;
                }
            }
        }
        
        if ($deleted > 0) {
            \Log::info("Cleaned up {$deleted} old PDF temp image files");
        }
    }
}
