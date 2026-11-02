<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ContentSplitterService
{
    /**
     * Maximum characters per chunk for A4 portrait PDF
     * Used as threshold to determine if splitting is needed
     * @var int
     */
    private $maxCharsPerChunk;

    /**
     * Chunk size when splitting (e.g., 500 chars per row)
     * @var int
     */
    private $chunkSize;

    /**
     * Minimum characters per chunk to avoid tiny fragments
     * @var int
     */
    private $minCharsPerChunk;

    /**
     * Constructor - load settings from config
     */
    public function __construct()
    {
        $this->maxCharsPerChunk = config('pdf_export.content_splitting.max_chars_per_chunk', 2700);
        $this->chunkSize = config('pdf_export.content_splitting.chunk_size', 500);
        $this->minCharsPerChunk = config('pdf_export.content_splitting.min_chars_per_chunk', 400);
    }

    /**
     * Split HTML content into chunks suitable for PDF rendering
     * 
     * @param string $htmlContent The HTML content to split
     * @param int|null $maxChars Optional max characters override
     * @param int|null $minChars Optional min characters override
     * @return array Array of HTML content chunks
     */
    /**
     * Convert all list tags to paragraph format for better PDF rendering
     * 
     * @param string $htmlContent
     * @return string
     */
    public function convertListsToParagraphs($htmlContent)
    {
        Log::info("ContentSplitterService: Converting lists to paragraphs for PDF rendering");
        
        $htmlContent = $this->convertUnorderedListsToParagraphs($htmlContent);
        
        $htmlContent = $this->convertOrderedListsToParagraphs($htmlContent);
        
        return $htmlContent;
    }

    public function split($htmlContent, $maxChars = null, $minChars = null)
    {
        if ($maxChars !== null) {
            $this->maxCharsPerChunk = $maxChars;
        }
        if ($minChars !== null) {
            $this->minCharsPerChunk = $minChars;
        }

        Log::info("ContentSplitterService: Starting content split");
        Log::info("ContentSplitterService: Settings - Split threshold: {$this->maxCharsPerChunk}, Chunk size: {$this->chunkSize}, Min chunk: {$this->minCharsPerChunk}");
        Log::info("ContentSplitterService: Original content length: " . strlen($htmlContent) . " bytes");

        $tableInfo = $this->analyzeTableContent($htmlContent);
        if ($tableInfo['is_table'] && $tableInfo['row_count'] >= config('pdf_export.content_splitting.table_splitting.min_rows_to_split', 15)) {
            Log::info("ContentSplitterService: Content is a large table with {$tableInfo['row_count']} rows - using table splitting");
            return $this->splitTableByRows($htmlContent, $tableInfo);
        }

        $htmlContent = $this->simplifyInlineStyles($htmlContent);

        $images = [];
        $contentWithoutImages = $this->extractBase64Images($htmlContent, $images);
        
        Log::info("ContentSplitterService: Extracted " . count($images) . " base64 images");
        Log::info("ContentSplitterService: Content length without images: " . strlen($contentWithoutImages) . " bytes");

        $textOnly = strip_tags($contentWithoutImages);
        $textLength = strlen($textOnly);
        
        Log::info("ContentSplitterService: Text-only length: {$textLength} characters");

        if ($textLength <= $this->maxCharsPerChunk) {
            Log::info("ContentSplitterService: Content is small enough, no splitting needed");
            return [$htmlContent];
        }

        $chunks = $this->splitAtLogicalBoundaries($contentWithoutImages, $textOnly);
        
        Log::info("ContentSplitterService: Split into " . count($chunks) . " chunks");

        $chunksWithImages = $this->restoreBase64Images($chunks, $images);
        
        foreach ($chunksWithImages as $index => $chunk) {
            $chunkTextLength = strlen(strip_tags($chunk));
            Log::info("ContentSplitterService: Chunk " . ($index + 1) . " text length: {$chunkTextLength} characters");
        }

        return $chunksWithImages;
    }

    /**
     * Simplify excessive inline styles that make safe splitting difficult
     * Removes or shortens very long style attributes while preserving essential formatting
     * 
     * @param string $htmlContent
     * @return string Content with simplified inline styles
     */
    private function simplifyInlineStyles($htmlContent)
    {
        $originalLength = strlen($htmlContent);
        
        $htmlContent = preg_replace_callback(
            '/style\s*=\s*["\']([^"\']{300,})["\']/',
            function($matches) {
                $styleLength = strlen($matches[1]);
                Log::info("ContentSplitterService: Removed excessive inline style ({$styleLength} chars)");
                return '';
            },
            $htmlContent
        );
        
        $htmlContent = preg_replace_callback(
            '/style\s*=\s*["\']([^"\']{100,299})["\']/',
            function($matches) {
                $originalStyle = $matches[1];
                
                $essentialProps = [];
                
                if (preg_match('/color:\s*([^;]+);?/', $originalStyle, $colorMatch)) {
                    $essentialProps[] = 'color: ' . trim($colorMatch[1]);
                }
                if (preg_match('/font-weight:\s*([^;]+);?/', $originalStyle, $weightMatch)) {
                    $essentialProps[] = 'font-weight: ' . trim($weightMatch[1]);
                }
                if (preg_match('/font-size:\s*([^;]+);?/', $originalStyle, $sizeMatch)) {
                    $essentialProps[] = 'font-size: ' . trim($sizeMatch[1]);
                }
                if (preg_match('/text-align:\s*([^;]+);?/', $originalStyle, $alignMatch)) {
                    $essentialProps[] = 'text-align: ' . trim($alignMatch[1]);
                }
                
                if (empty($essentialProps)) {
                    return '';
                }
                
                $simplifiedStyle = implode('; ', $essentialProps) . ';';
                Log::info("ContentSplitterService: Simplified style from " . strlen($originalStyle) . " to " . strlen($simplifiedStyle) . " chars");
                return 'style="' . $simplifiedStyle . '"';
            },
            $htmlContent
        );
        
        $newLength = strlen($htmlContent);
        $reduction = $originalLength - $newLength;
        
        if ($reduction > 0) {
            Log::info("ContentSplitterService: Reduced content size by {$reduction} bytes through style simplification");
        }
        
        return $htmlContent;
    }

    /**
     * Analyze HTML content to detect if it's primarily a table
     * 
     * @param string $htmlContent
     * @return array ['is_table' => bool, 'row_count' => int, 'table_html' => string]
     */
    private function analyzeTableContent($htmlContent)
    {
        $result = [
            'is_table' => false,
            'row_count' => 0,
            'table_html' => '',
            'before_table' => '',
            'after_table' => ''
        ];

        if (!preg_match('/<table[^>]*>/i', $htmlContent)) {
            return $result;
        }

        if (preg_match('/(.*?)<table([^>]*)>(.*?)<\/table>(.*)/is', $htmlContent, $matches)) {
            $beforeTable = trim($matches[1]);
            $tableAttributes = $matches[2];
            $tableContent = $matches[3];
            $afterTable = trim($matches[4]);

            $rowCount = preg_match_all('/<tr[^>]*>/i', $tableContent);
            
            $totalLength = strlen(strip_tags($htmlContent));
            $tableLength = strlen(strip_tags($tableContent));
            $isTablePrimary = ($tableLength / $totalLength) > 0.7;

            Log::info("ContentSplitterService: Table detected - {$rowCount} rows, " . 
                     round(($tableLength / $totalLength) * 100, 1) . "% of content");

            $result = [
                'is_table' => $isTablePrimary,
                'row_count' => $rowCount,
                'table_html' => '<table' . $tableAttributes . '>' . $tableContent . '</table>',
                'table_attributes' => $tableAttributes,
                'table_content' => $tableContent,
                'before_table' => $beforeTable,
                'after_table' => $afterTable
            ];
        }

        return $result;
    }

    /**
     * Split a large table into multiple chunks by row count
     * 
     * @param string $htmlContent
     * @param array $tableInfo
     * @return array Array of HTML chunks
     */
    private function splitTableByRows($htmlContent, $tableInfo)
    {
        $rowsPerChunk = config('pdf_export.content_splitting.table_splitting.rows_per_chunk', 10);
        $headerRowCount = config('pdf_export.content_splitting.table_splitting.header_rows', 1);
        
        Log::info("ContentSplitterService: Splitting table - {$rowsPerChunk} rows per chunk, {$headerRowCount} header rows");

        preg_match_all('/<tr[^>]*>.*?<\/tr>/is', $tableInfo['table_content'], $rowMatches);
        $allRows = $rowMatches[0];
        
        if (count($allRows) === 0) {
            Log::warning("ContentSplitterService: No rows found in table, returning original content");
            return [$htmlContent];
        }

        $headerRows = array_slice($allRows, 0, $headerRowCount);
        $dataRows = array_slice($allRows, $headerRowCount);

        Log::info("ContentSplitterService: Found " . count($headerRows) . " header rows and " . count($dataRows) . " data rows");

        $rowChunks = $this->splitRowsRespectingRowspan($dataRows, $rowsPerChunk);
        $chunks = [];

        foreach ($rowChunks as $chunkIndex => $rowChunk) {
            $chunkHtml = '';
            
            if ($chunkIndex === 0 && !empty($tableInfo['before_table'])) {
                $chunkHtml .= $tableInfo['before_table'] . "\n";
            }

            $chunkHtml .= '<table' . $tableInfo['table_attributes'] . '>' . "\n";
            
            if ($chunkIndex === 0) {
                foreach ($headerRows as $headerRow) {
                    $chunkHtml .= $headerRow . "\n";
                }
                Log::info("ContentSplitterService: Added headers to first chunk");
            } else {
                Log::info("ContentSplitterService: Skipped headers for chunk " . ($chunkIndex + 1) . " (continuous table)");
            }
            
            foreach ($rowChunk as $dataRow) {
                $chunkHtml .= $dataRow . "\n";
            }
            
            $chunkHtml .= '</table>';

            if ($chunkIndex === count($rowChunks) - 1 && !empty($tableInfo['after_table'])) {
                $chunkHtml .= "\n" . $tableInfo['after_table'];
            }

            $chunks[] = trim($chunkHtml);
            
            Log::info("ContentSplitterService: Chunk " . ($chunkIndex + 1) . " contains " . count($rowChunk) . " rows");
        }

        Log::info("ContentSplitterService: Table split into " . count($chunks) . " chunks");
        
        return $chunks;
    }

    /**
     * Split rows into chunks while respecting rowspan boundaries
     * 
     * @param array $rows
     * @param int $rowsPerChunk
     * @return array
     */
    private function splitRowsRespectingRowspan($rows, $rowsPerChunk)
    {
        $chunks = [];
        $currentChunk = [];
        $activeRowspans = [];
        
        foreach ($rows as $rowIndex => $row) {
            $currentChunk[] = $row;
            
            foreach ($activeRowspans as $colIndex => $remainingRows) {
                $activeRowspans[$colIndex]--;
                if ($activeRowspans[$colIndex] <= 0) {
                    unset($activeRowspans[$colIndex]);
                }
            }
            
            if (preg_match_all('/rowspan=["\'](\d+)["\']/i', $row, $matches)) {
                foreach ($matches[1] as $rowspanValue) {
                    $rowspanCount = (int)$rowspanValue;
                    if ($rowspanCount > 1) {
                        $activeRowspans[] = $rowspanCount - 1;
                        Log::info("ContentSplitterService: Detected rowspan={$rowspanCount} at row " . ($rowIndex + 1));
                    }
                }
            }
            
            $shouldSplit = count($currentChunk) >= $rowsPerChunk && empty($activeRowspans);
            
            if ($shouldSplit) {
                $chunks[] = $currentChunk;
                Log::info("ContentSplitterService: Created chunk with " . count($currentChunk) . " rows (no active rowspans)");
                $currentChunk = [];
            } elseif (count($currentChunk) >= $rowsPerChunk && !empty($activeRowspans)) {
                $maxRemaining = max($activeRowspans);
                Log::info("ContentSplitterService: Chunk has {$maxRemaining} remaining rowspan rows, continuing...");
            }
        }
        
        if (!empty($currentChunk)) {
            $chunks[] = $currentChunk;
            Log::info("ContentSplitterService: Created final chunk with " . count($currentChunk) . " rows");
        }
        
        return $chunks;
    }

    /**
     * Extract base64 images from HTML and replace with placeholders
     * 
     * @param string $htmlContent
     * @param array &$images Reference to array to store extracted images
     * @return string Content with image placeholders
     */
    private function extractBase64Images($htmlContent, &$images)
    {
        $pattern = '/<img[^>]+src=["\']data:image\/[^;]+;base64,[^"\']+["\'][^>]*>/i';
        
        $contentWithPlaceholders = preg_replace_callback(
            $pattern,
            function($match) use (&$images) {
                $placeholder = "{{IMAGE_PLACEHOLDER_" . count($images) . "}}";
                $images[] = $match[0];
                return $placeholder;
            },
            $htmlContent
        );

        return $contentWithPlaceholders;
    }

    /**
     * Restore base64 images back into content chunks
     * 
     * @param array $chunks
     * @param array $images
     * @return array Chunks with images restored
     */
    private function restoreBase64Images($chunks, $images)
    {
        if (empty($images)) {
            return $chunks;
        }

        $restoredChunks = [];
        
        foreach ($chunks as $chunk) {
            $restoredChunk = $chunk;
            
            foreach ($images as $index => $imageHtml) {
                $placeholder = "{{IMAGE_PLACEHOLDER_{$index}}}";
                $restoredChunk = str_replace($placeholder, $imageHtml, $restoredChunk);
            }
            
            $restoredChunks[] = $restoredChunk;
        }

        return $restoredChunks;
    }

    /**
     * Split content at logical HTML boundaries
     * 
     * @param string $contentWithoutImages Content with image placeholders
     * @param string $textOnly Plain text version for length calculations
     * @return array Array of content chunks
     */
    private function splitAtLogicalBoundaries($contentWithoutImages, $textOnly)
    {
        $chunks = [];
        $currentPosition = 0;
        $contentLength = strlen($contentWithoutImages);
        
        while ($currentPosition < $contentLength) {
            $targetPosition = $currentPosition + $this->chunkSize;
            
            if ($targetPosition >= $contentLength) {
                $chunks[] = substr($contentWithoutImages, $currentPosition);
                break;
            }
            
            $splitPosition = $this->findBestSplitPoint(
                $contentWithoutImages, 
                $currentPosition,
                $targetPosition
            );

            if ($splitPosition <= $currentPosition) {
                Log::warning("ContentSplitterService: Split position ({$splitPosition}) not advancing from current ({$currentPosition}), forcing advance");
                $splitPosition = min($currentPosition + 100, $contentLength);
            }
            
            $chunkLength = $splitPosition - $currentPosition;
            $chunk = substr($contentWithoutImages, $currentPosition, $chunkLength);
            
            $chunkTextLength = strlen(strip_tags($chunk));
            if ($chunkTextLength < $this->minCharsPerChunk && $splitPosition < $contentLength) {
                $newSplitPosition = $this->findBestSplitPoint(
                    $contentWithoutImages,
                    $currentPosition,
                    $currentPosition + $this->minCharsPerChunk + 500
                );
                
                if ($newSplitPosition > $splitPosition) {
                    $splitPosition = $newSplitPosition;
                    $chunkLength = $splitPosition - $currentPosition;
                    $chunk = substr($contentWithoutImages, $currentPosition, $chunkLength);
                }
            }
            
            $chunks[] = $chunk;
            $currentPosition = $splitPosition;
        }
        
        return $chunks;
    }

    /**
     * Find the best position to split content based on HTML structure
     * 
     * @param string $content
     * @param int $startPos
     * @param int $targetPos
     * @return int Best split position
     */
    private function findBestSplitPoint($content, $startPos, $targetPos)
    {
        $splitPatterns = [
            '</p>' => 100,
            '</ul>' => 90,
            '</ol>' => 90,
            '</li>' => 50,
            '<br>' => 30,
            '<br/>' => 30,
            '<br />' => 30,
        ];

        $bestSplitPos = $targetPos;
        $bestScore = 0;
        $searchStart = max($startPos, $targetPos - 500);
        $searchEnd = min(strlen($content), $targetPos + 500);
        
        foreach ($splitPatterns as $pattern => $score) {
            $patternPos = $targetPos;
            
            $searchPos = $targetPos;
            while ($searchPos > $searchStart) {
                $foundPos = strrpos(substr($content, $searchStart, $searchPos - $searchStart), $pattern);
                if ($foundPos !== false) {
                    $actualPos = $searchStart + $foundPos + strlen($pattern);
                    
                    $distance = abs($actualPos - $targetPos);
                    $distanceScore = max(0, 100 - ($distance / 10));
                    
                    $totalScore = $score + $distanceScore;
                    
                    if ($totalScore > $bestScore) {
                        $bestScore = $totalScore;
                        $bestSplitPos = $actualPos;
                    }
                    break;
                }
                $searchPos -= 100;
            }
            
            if ($targetPos < strlen($content)) {
                $foundPos = strpos($content, $pattern, $targetPos);
                if ($foundPos !== false && $foundPos < $searchEnd) {
                    $actualPos = $foundPos + strlen($pattern);
                    $distance = abs($actualPos - $targetPos);
                    $distanceScore = max(0, 100 - ($distance / 10));
                    $totalScore = $score + $distanceScore;
                    
                    if ($totalScore > $bestScore) {
                        $bestScore = $totalScore;
                        $bestSplitPos = $actualPos;
                    }
                }
            }
        }
        
        if ($bestScore == 0) {
            Log::warning("ContentSplitterService: No optimal split point found, searching for safe split position");
            $bestSplitPos = $this->findSafeSplitPosition($content, $startPos, $targetPos);
        }
        
        return $bestSplitPos;
    }

    /**
     * Find a safe position to split content (not inside HTML tags)
     * Searches for spaces outside of HTML tags near the target position
     * 
     * @param string $content
     * @param int $startPos Minimum position (don't go before this)
     * @param int $targetPos Target position for split
     * @return int Safe split position
     */
    private function findSafeSplitPosition($content, $startPos, $targetPos)
    {
        $contentLength = strlen($content);
        $targetPos = min($targetPos, $contentLength);
        
        $searchRadius = 800;
        
        for ($offset = 0; $offset <= $searchRadius; $offset++) {
            $pos = $targetPos - $offset;
            if ($pos > $startPos) {
                if ($content[$pos] === '>' && 
                    ($pos + 1 < $contentLength) && 
                    ($content[$pos + 1] === ' ' || $content[$pos + 1] === "\n" || ctype_alpha($content[$pos + 1]))) {
                    $splitPos = $pos + 1;
                    if ($splitPos > $startPos) {
                        Log::info("ContentSplitterService: Found safe split after closing tag at position " . $splitPos . " (" . $offset . " chars before target)");
                        return $splitPos;
                    }
                }
            }
            
            $pos = $targetPos + $offset;
            if ($pos > $startPos && $pos < $contentLength) {
                if ($content[$pos] === '>' && 
                    ($pos + 1 < $contentLength) && 
                    ($content[$pos + 1] === ' ' || $content[$pos + 1] === "\n" || ctype_alpha($content[$pos + 1]))) {
                    $splitPos = $pos + 1;
                    if ($splitPos > $startPos) {
                        Log::info("ContentSplitterService: Found safe split after closing tag at position " . $splitPos . " (" . $offset . " chars after target)");
                        return $splitPos;
                    }
                }
            }
        }
        
        for ($offset = 0; $offset <= $searchRadius; $offset++) {
            $pos = $targetPos - $offset;
            if ($pos > $startPos && $content[$pos] === ' ' && !$this->isInsideHtmlTag($content, $pos)) {
                Log::info("ContentSplitterService: Found safe split at space position " . $pos . " (" . $offset . " chars before target)");
                return $pos;
            }
            
            $pos = $targetPos + $offset;
            if ($pos > $startPos && $pos < $contentLength && $content[$pos] === ' ' && !$this->isInsideHtmlTag($content, $pos)) {
                Log::info("ContentSplitterService: Found safe split at space position " . $pos . " (" . $offset . " chars after target)");
                return $pos;
            }
        }
        
        $nearestTagEnd = $this->findNearestCompleteTag($content, $targetPos, $startPos);
        if ($nearestTagEnd !== false && $nearestTagEnd > $startPos) {
            Log::info("ContentSplitterService: Found safe split at end of complete tag at position " . $nearestTagEnd);
            return $nearestTagEnd;
        }
        
        Log::error("ContentSplitterService: Could not find safe split position after extensive search, forcing split at target position (HTML may break)");
        
        if ($targetPos <= $startPos) {
            $targetPos = min($startPos + 100, $contentLength);
        }
        
        return $targetPos;
    }

    /**
     * Find the nearest position after a complete HTML tag
     * 
     * @param string $content
     * @param int $targetPos
     * @param int $startPos Minimum position to search (don't go before this)
     * @return int|false Position after complete tag, or false if not found
     */
    private function findNearestCompleteTag($content, $targetPos, $startPos = 0)
    {
        $searchLimit = max($startPos, $targetPos - 2000);
        
        for ($i = $targetPos; $i > $searchLimit; $i--) {
            if ($content[$i] === '>') {
                $foundPos = $i + 1;
                if ($foundPos > $startPos) {
                    return $foundPos;
                }
            }
        }
        
        for ($i = $targetPos; $i > $startPos; $i--) {
            if ($content[$i] === '>') {
                $foundPos = $i + 1;
                if ($foundPos > $startPos) {
                    Log::warning("ContentSplitterService: Had to search very far back (>" . ($targetPos - $i) . " chars) to find safe split");
                    return $foundPos;
                }
            }
        }
        
        return false;
    }

    /**
     * Check if a position in the content is inside an HTML tag
     * 
     * @param string $content
     * @param int $pos
     * @return bool True if position is inside an HTML tag
     */
    private function isInsideHtmlTag($content, $pos)
    {
        $lastOpenTag = strrpos(substr($content, 0, $pos), '<');
        $lastCloseTag = strrpos(substr($content, 0, $pos), '>');
        
        if ($lastOpenTag !== false && ($lastCloseTag === false || $lastOpenTag > $lastCloseTag)) {
            return true;
        }
        
        return false;
    }

    /**
     * Check if content should be kept together (don't split)
     * 
     * @param string $content
     * @return bool True if content should not be split
     */
    private function shouldKeepTogether($content)
    {
        if (preg_match('/<table[^>]*>/i', $content)) {
            return true;
        }
        
        if (preg_match('/^[\s]*<h[1-6][^>]*>/i', $content)) {
            $textLength = strlen(strip_tags($content));
            if ($textLength < $this->minCharsPerChunk) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Convert ordered lists (<ol>) to paragraphs with numbering
     * Handles nested lists properly by converting from innermost to outermost
     * 
     * @param string $html
     * @return string
     */
    private function convertOrderedListsToParagraphs($html)
    {
        $maxIterations = 10;
        $iteration = 0;
        
        while (preg_match('/<ol[^>]*>.*?<\/ol>/is', $html) && $iteration < $maxIterations) {
            $html = preg_replace_callback(
                '/<ol([^>]*)>((?:(?!<(?:ol|ul)[^>]*>).)*?)<\/ol>/is',
                function($matches) {
                    $olAttributes = $matches[1];
                    $content = $matches[2];
                    
                    $startNum = 1;
                    if (preg_match('/start=["\']?(\d+)["\']?/i', $olAttributes, $startMatch)) {
                        $startNum = (int)$startMatch[1];
                    }
                    
                    $indentLevel = 0;
                    if (preg_match('/class=["\']([^"\']*ql-indent-(\d+)[^"\']*)["\']/i', $olAttributes, $indentMatch)) {
                        $indentLevel = (int)$indentMatch[2];
                    }
                    
                    preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $content, $items);
                    
                    $result = '';
                    $currentNum = $startNum;
                    
                    foreach ($items[1] as $itemContent) {
                        $indentPx = $indentLevel * 30;
                        
                        $cleanContent = trim($itemContent);
                        $cleanContent = preg_replace('/^<p[^>]*>(.*?)<\/p>(.*)$/is', '$1$2', $cleanContent);
                        
                        $result .= '<p style="margin: 0 0 6px ' . $indentPx . 'px; padding: 0; line-height: 1.5; text-indent: -20px; padding-left: 20px;">';
                        $result .= $currentNum . '. ' . $cleanContent;
                        $result .= '</p>' . "\n";
                        $currentNum++;
                    }
                    
                    return $result;
                },
                $html
            );
            $iteration++;
        }
        
        return $html;
    }

    /**
     * Convert unordered lists (<ul>) to paragraphs with bullets
     * Handles nested lists properly by converting from innermost to outermost
     * 
     * @param string $html
     * @return string
     */
    private function convertUnorderedListsToParagraphs($html)
    {
        $maxIterations = 10;
        $iteration = 0;
        
        while (preg_match('/<ul[^>]*>.*?<\/ul>/is', $html) && $iteration < $maxIterations) {
            $html = preg_replace_callback(
                '/<ul([^>]*)>((?:(?!<(?:ul|ol)[^>]*>).)*?)<\/ul>/is',
                function($matches) {
                    $ulAttributes = $matches[1];
                    $content = $matches[2];
                    
                    $indentLevel = 0;
                    if (preg_match('/class=["\']([^"\']*ql-indent-(\d+)[^"\']*)["\']/i', $ulAttributes, $indentMatch)) {
                        $indentLevel = (int)$indentMatch[2];
                    }
                    
                    $bulletSymbol = '•';
                    if ($indentLevel === 1) {
                        $bulletSymbol = '◦';
                    } elseif ($indentLevel >= 2) {
                        $bulletSymbol = '▪';
                    }
                    
                    preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $content, $items);
                    
                    $result = '';
                    
                    foreach ($items[1] as $itemContent) {
                        $indentPx = $indentLevel * 30;
                        
                        $cleanContent = trim($itemContent);
                        $cleanContent = preg_replace('/^<p[^>]*>(.*?)<\/p>(.*)$/is', '$1$2', $cleanContent);
                        
                        $result .= '<p style="margin: 0 0 6px ' . $indentPx . 'px; padding: 0; line-height: 1.5; padding-left: 15px;">';
                        $result .= $bulletSymbol . ' ' . $cleanContent;
                        $result .= '</p>' . "\n";
                    }
                    
                    return $result;
                },
                $html
            );
            $iteration++;
        }
        
        return $html;
    }
    
    /**
     * Split long content for PDF rendering
     * 
     * @param \Illuminate\Support\Collection $documentTitles
     * @param bool $isPublished
     * @return \Illuminate\Support\Collection
     */
    public function splitLongContentForPDF($documentTitles, $isPublished = true)
    {
        $processedTitles = collect();
        
        $richTextModel = $isPublished 
            ? \App\GovernanceHubDocumentContentRichText::class 
            : \App\GovernanceHubDraftContentRichText::class;
        
        foreach ($documentTitles as $title) {
            $richTextContents = $richTextModel::where('content_id', $title->id)
                ->where('is_deleted', false)
                ->orderBy('position', 'asc')
                ->get();
            
            if ($richTextContents->isEmpty()) {
                \Log::info("  No rich text content for title ID {$title->id}");
                $processedTitles->push($title);
                continue;
            }
            
            $totalTextLength = 0;
            $hasTable = false;
            $needsSplitting = false;
            
            foreach ($richTextContents as $richText) {
                if ($richText->rich_text) {
                    $textOnly = strip_tags($richText->rich_text);
                    $totalTextLength += strlen($textOnly);
                    
                    if (preg_match('/<table[^>]*>/i', $richText->rich_text)) {
                        $hasTable = true;
                    }
                }
            }
            
            $splitThreshold = config('pdf_export.content_splitting.split_threshold', 2700);
            
            if ($totalTextLength > $splitThreshold || $hasTable) {
                $needsSplitting = true;
                \Log::info("  Title ID {$title->id} needs splitting: {$totalTextLength} chars" . ($hasTable ? " (has table)" : ""));
            }
            
            if (!$needsSplitting) {
                \Log::info("  Final decision: No splitting for title ID {$title->id}");
                
                foreach ($richTextContents as $richText) {
                    if ($richText->rich_text) {
                        $richText->rich_text = trim($richText->rich_text);
                        $richText->rich_text = $this->convertListsToParagraphs($richText->rich_text);
                        $richText->responsibility = trim($richText->responsibility);
                    }
                }
                
                $processedTitles->push($title);
                continue;
            }
            
            \Log::info("  Final decision: Splitting title ID {$title->id}");
            
            $splitIndex = 0;
            $isFirstItem = true;
            
            foreach ($richTextContents as $richTextIndex => $richText) {
                if (!$richText->rich_text) {
                    $clonedTitle = clone $title;
                    $clonedTitle->_single_rich_text = $richText;
                    $clonedTitle->_is_split = false;
                    $clonedTitle->_hide_section_header = !$isFirstItem;
                    $processedTitles->push($clonedTitle);
                    $isFirstItem = false;
                    continue;
                }
                
                $richText->rich_text = trim($richText->rich_text);
                $richText->responsibility = trim($richText->responsibility);
                
                $richText->rich_text = $this->convertListsToParagraphs($richText->rich_text);
                
                $hasTable = preg_match('/<table[^>]*>/i', $richText->rich_text);
                
                $textOnly = strip_tags($richText->rich_text);
                
                $splitThreshold = config('pdf_export.content_splitting.split_threshold', 2700);
                
                if (strlen($textOnly) > $splitThreshold || $hasTable) {
                    \Log::info("Splitting rich text ID {$richText->id}: " . strlen($textOnly) . " characters" . ($hasTable ? " (contains table)" : ""));
                    
                    $chunks = $this->split($richText->rich_text);
                    
                    \Log::info("Split into " . count($chunks) . " chunks");
                    
                    foreach ($chunks as $chunkIndex => $chunk) {
                        $clonedTitle = clone $title;
                        $clonedRichText = clone $richText;
                        $clonedRichText->rich_text = $chunk;
                        
                        $clonedTitle->_single_rich_text = $clonedRichText;
                        $clonedTitle->_is_split = true;
                        $clonedTitle->_split_part = $chunkIndex + 1;
                        $clonedTitle->_split_total = count($chunks);
                        $clonedTitle->_hide_section_header = !$isFirstItem;
                        
                        $processedTitles->push($clonedTitle);
                        $isFirstItem = false;
                        $splitIndex++;
                    }
                } else {
                    $clonedTitle = clone $title;
                    $clonedTitle->_single_rich_text = $richText;
                    $clonedTitle->_is_split = false;
                    $clonedTitle->_hide_section_header = !$isFirstItem; // Hide header for subsequent items
                    $processedTitles->push($clonedTitle);
                    $isFirstItem = false;
                }
            }
        }
        
        \Log::info("Content splitting complete: " . $documentTitles->count() . " titles → " . $processedTitles->count() . " processed entries");
        
        return $processedTitles;
    }
    
    /**
     * Flatten tables with rowspan by duplicating cell content across rows
     * This converts complex tables into simpler ones that can be split cleanly
     * 
     * @param string $htmlContent
     * @return string
     */
    public function flattenRowspanTables($htmlContent)
    {
        \Log::info("Starting rowspan flattening process");
        
        preg_match_all('/<table[^>]*>.*?<\/table>/is', $htmlContent, $tableMatches);
        
        if (empty($tableMatches[0])) {
            return $htmlContent;
        }
        
        $flattened = 0;
        foreach ($tableMatches[0] as $originalTable) {
            if (stripos($originalTable, 'rowspan') === false) {
                continue;
            }
            
            \Log::info("Found table with rowspan, attempting to flatten...");
            
            try {
                $flattenedTable = $this->flattenSingleTableRowspan($originalTable);
                $htmlContent = str_replace($originalTable, $flattenedTable, $htmlContent);
                $flattened++;
                \Log::info("Successfully flattened table #{$flattened}");
            } catch (\Exception $e) {
                \Log::error("Failed to flatten rowspan table: " . $e->getMessage());
            }
        }
        
        if ($flattened > 0) {
            \Log::info("Flattened {$flattened} table(s) with rowspan");
        }
        
        return $htmlContent;
    }
    
    /**
     * Flatten a single table by removing rowspan and duplicating content down rows
     * 
     * @param string $tableHtml
     * @return string
     */
    private function flattenSingleTableRowspan($tableHtml)
    {
        preg_match('/<table([^>]*)>/i', $tableHtml, $tableTagMatch);
        $tableAttrs = $tableTagMatch[1] ?? '';
        
        preg_match('/<colgroup[^>]*>.*?<\/colgroup>/is', $tableHtml, $colgroupMatch);
        $colgroup = $colgroupMatch[0] ?? '';
        
        preg_match_all('/<tr([^>]*)>(.*?)<\/tr>/is', $tableHtml, $rowMatches, PREG_SET_ORDER);
        
        if (empty($rowMatches)) {
            return $tableHtml;
        }
        
        $grid = [];
        $rowAttrs = [];
        $currentRow = 0;
        
        foreach ($rowMatches as $rowMatch) {
            $rowAttr = $rowMatch[1];
            $rowContent = $rowMatch[2];
            $rowAttrs[$currentRow] = $rowAttr;
            
            preg_match_all('/<(td|th)([^>]*)>(.*?)<\/\1>/is', $rowContent, $cellMatches, PREG_SET_ORDER);
            
            $currentCol = 0;
            
            foreach ($cellMatches as $cellMatch) {
                $cellTag = $cellMatch[1];
                $cellAttrs = $cellMatch[2];
                $cellContent = $cellMatch[3];
                
                while (isset($grid[$currentRow][$currentCol])) {
                    $currentCol++;
                }
                
                $rowspan = 1;
                $colspan = 1;
                
                if (preg_match('/rowspan=["\']?(\d+)["\']?/i', $cellAttrs, $rowspanMatch)) {
                    $rowspan = intval($rowspanMatch[1]);
                }
                if (preg_match('/colspan=["\']?(\d+)["\']?/i', $cellAttrs, $colspanMatch)) {
                    $colspan = intval($colspanMatch[1]);
                }
                
                $cellAttrs = preg_replace('/\s*rowspan=["\']?\d+["\']?/i', '', $cellAttrs);
                
                for ($r = 0; $r < $rowspan; $r++) {
                    for ($c = 0; $c < $colspan; $c++) {
                        $grid[$currentRow + $r][$currentCol + $c] = [
                            'tag' => $cellTag,
                            'attrs' => $cellAttrs,
                            'content' => $cellContent
                        ];
                    }
                }
                
                $currentCol += $colspan;
            }
            
            $currentRow++;
        }
        
        $newTable = "<table{$tableAttrs}>\n";
        
        if ($colgroup) {
            $newTable .= $colgroup . "\n";
        }
        
        $newTable .= "<tbody>\n";
        
        foreach ($grid as $rowIndex => $rowCells) {
            $newTable .= "<tr" . ($rowAttrs[$rowIndex] ?? '') . ">";
            
            ksort($rowCells);
            $lastCol = -1;
            
            foreach ($rowCells as $colIndex => $cell) {
                if ($colIndex <= $lastCol) {
                    continue;
                }
                
                $colspan = 1;
                $nextCol = $colIndex + 1;
                while (isset($rowCells[$nextCol]) && 
                       $rowCells[$nextCol]['content'] === $cell['content'] &&
                       $rowCells[$nextCol]['tag'] === $cell['tag']) {
                    $colspan++;
                    $nextCol++;
                }
                
                $attrs = $cell['attrs'];
                if ($colspan > 1) {
                    $attrs = preg_replace('/\s*colspan=["\']?\d+["\']?/i', '', $attrs);
                    $attrs .= ' colspan="' . $colspan . '"';
                }
                
                $newTable .= "<{$cell['tag']}{$attrs}>{$cell['content']}</{$cell['tag']}>";
                $lastCol = $colIndex + $colspan - 1;
            }
            
            $newTable .= "</tr>\n";
        }
        
        $newTable .= "</tbody>\n</table>";
        
        return $newTable;
    }
    
    /**
     * Split large tables into smaller tables for better PDF pagination
     * 
     * @param string $htmlContent HTML content containing tables
     * @param int $maxRowsPerTable Maximum rows per table chunk (default: 10)
     * @return string Modified HTML with split tables
     */
    public function splitLargeTables($htmlContent, $maxRowsPerTable = 10)
    {
        if (strpos($htmlContent, '<table') === false) {
            return $htmlContent;
        }

        \Log::info("Starting table splitting process (max rows per table: {$maxRowsPerTable})");
        
        preg_match_all('/<table[^>]*>.*?<\/table>/is', $htmlContent, $tableMatches);
        
        if (empty($tableMatches[0])) {
            \Log::info("No tables found to split");
            return $htmlContent;
        }

        $tableCount = count($tableMatches[0]);
        \Log::info("Found {$tableCount} table(s) to analyze");

        foreach ($tableMatches[0] as $originalTable) {
            $hasRowspan = stripos($originalTable, 'rowspan') !== false;
            
            if ($hasRowspan) {
                \Log::warning("Table still contains rowspan after flattening - skipping split");
                continue;
            }
            
            $effectiveMaxRows = $maxRowsPerTable;

            preg_match('/<table[^>]*>/i', $originalTable, $tableTagMatch);
            $tableOpenTag = $tableTagMatch[0] ?? '<table>';

            preg_match('/<thead[^>]*>.*?<\/thead>/is', $originalTable, $theadMatch);
            $thead = $theadMatch[0] ?? '';

            if (preg_match('/<tbody[^>]*>(.*?)<\/tbody>/is', $originalTable, $tbodyMatch)) {
                $tbodyContent = $tbodyMatch[1];
                preg_match_all('/<tr[^>]*>.*?<\/tr>/is', $tbodyContent, $rowMatches);
            } else {
                $contentAfterThead = preg_replace('/<thead[^>]*>.*?<\/thead>/is', '', $originalTable);
                preg_match_all('/<tr[^>]*>.*?<\/tr>/is', $contentAfterThead, $rowMatches);
            }

            $rows = $rowMatches[0] ?? [];
            $totalRowCount = count($rows);

            $headerRows = [];
            $headerCount = 0;
            foreach ($rows as $index => $row) {
                if ($index < 3) {
                    $headerRows[] = $row;
                    $headerCount++;
                    \Log::info("Detected header row " . ($index + 1));
                    continue;
                }
                
                if (preg_match('/colspan\s*=\s*["\']?([5-9]|[1-9]\d+)["\']?/i', $row)) {
                    $headerRows[] = $row;
                    $headerCount++;
                    \Log::info("Detected additional header row with large colspan at position " . ($index + 1));
                } else {
                    break;
                }
            }

            $dataRows = array_slice($rows, $headerCount);
            $dataRowCount = count($dataRows);

            \Log::info("Table has {$totalRowCount} total rows: {$headerCount} header rows + {$dataRowCount} data rows");

            if ($dataRowCount <= $effectiveMaxRows) {
                \Log::info("Table is small enough ({$dataRowCount} data rows <= {$effectiveMaxRows}), no split needed");
                continue;
            }

            \Log::info("Splitting table with {$dataRowCount} data rows into chunks of {$effectiveMaxRows} rows");
            
            $chunks = array_chunk($dataRows, $effectiveMaxRows);
            $newTables = [];
            
            foreach ($chunks as $index => $chunkRows) {
                $chunkNum = $index + 1;
                $chunkRowCount = count($chunkRows);
                
                $newTable = $tableOpenTag . "\n";
                
                if ($thead) {
                    $newTable .= $thead . "\n";
                }
                
                $newTable .= "<tbody>\n";
                
                if (!empty($headerRows)) {
                    $newTable .= implode("\n", $headerRows) . "\n";
                    \Log::info("Added {$headerCount} header rows to chunk {$chunkNum}");
                }
                
                $newTable .= implode("\n", $chunkRows);
                $newTable .= "\n</tbody>\n";
                $newTable .= "</table>";
                
                $newTables[] = $newTable;
                
                \Log::info("Created table chunk {$chunkNum} with {$headerCount} header rows + {$chunkRowCount} data rows");
            }

            $splitTable = implode("\n<div style=\"page-break-after: always; height: 0;\"></div>\n", $newTables);
            
            $htmlContent = str_replace($originalTable, $splitTable, $htmlContent);
            
            \Log::info("Successfully split table into " . count($newTables) . " chunks");
        }

        return $htmlContent;
    }
}

