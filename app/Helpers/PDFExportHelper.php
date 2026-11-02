<?php

namespace App\Helpers;

class PDFExportHelper
{
    /**
     * Clean and optimize rich text content for PDF rendering
     */
    public static function optimizeRichTextForPDF($richText, $maxLength = 5000)
    {
        if (empty($richText)) {
            return $richText;
        }

        // Remove problematic elements that don't render well in PDF
        $richText = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $richText);
        $richText = preg_replace('/<iframe[^>]*>.*?<\/iframe>/is', '', $richText);

        // Optimize images for PDF
        $richText = preg_replace_callback(
            '/<img([^>]*?)>/i',
            function($matches) {
                $imgTag = $matches[1];
                // Remove problematic style attributes
                $imgTag = preg_replace('/style\s*=\s*["\'][^"\']*["\']/', '', $imgTag);
                // Add PDF-optimized styling
                return '<img' . $imgTag . '">';
            },
            $richText
        );

        // Optimize tables for better PDF rendering
        $richText = preg_replace_callback(
            '/<table([^>]*?)>/i',
            function($matches) use ($maxLength, $richText) {
                $tableTag = $matches[1];
                $contentLength = strlen($richText);

                if ($contentLength > $maxLength) {
                    return '<table' . $tableTag . ' class="long-content" style="font-size:8px;width:100%;table-layout:fixed;">';
                }
                return '<table' . $tableTag . ' style="width:100%;table-layout:fixed;">';
            },
            $richText
        );

        // Fix table cell styling
        return preg_replace(
            '/<td([^>]*?)>/i',
            '<td$1 style="word-wrap:break-word;padding:2px;vertical-align:top;border:1px solid #ddd;">',
            $richText
        );
    }
}
