<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PDF Content Splitting Configuration
    |--------------------------------------------------------------------------
    |
    | These settings control how long content is split into chunks for PDF
    | rendering. Adjust these values based on your paper size and layout.
    |
    */

    'content_splitting' => [
        // Maximum characters per chunk for A4 portrait PDF
        // Used as the threshold to decide if splitting is needed
        'max_chars_per_chunk' => 2700,

        // Character threshold to trigger splitting
        // Only split content if it exceeds this length
        'split_threshold' => 2700,

        // Chunk size when splitting (500 chars per row)
        // When content > split_threshold, split into this size
        'chunk_size' => 500,

        // Minimum characters per chunk to avoid tiny fragments
        // This prevents creating very small chunks at the end
        'min_chars_per_chunk' => 400,
        
        // Table-specific splitting settings
        'table_splitting' => [
            // Minimum number of rows to trigger table splitting
            'min_rows_to_split' => 15,
            
            // Number of rows per chunk when splitting tables
            // Increased to 15 to reduce visual fragmentation
            'rows_per_chunk' => 15,
            
            // Number of header rows (shown only at the beginning of the table)
            // For complex tables with multiple header rows (e.g., title + column headers + subheaders)
            // Note: Headers appear ONLY in the first chunk to create a continuous table flow
            'header_rows' => 3,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Font Scaling Configuration
    |--------------------------------------------------------------------------
    |
    | Font size multipliers for different paper sizes
    |
    */

    'font_scaling' => [
        'A4' => 1.1,  // 10% increase for A4 paper
        'A3' => 1.0,  // No adjustment for A3 paper
    ],

    /*
    |--------------------------------------------------------------------------
    | PDF Paper Size Configuration
    |--------------------------------------------------------------------------
    |
    | Default paper size and orientation settings
    |
    */

    'paper_size' => [
        'default' => 'A4',
        'orientation' => 'portrait',
        'dpi' => 120,
    ],
];

