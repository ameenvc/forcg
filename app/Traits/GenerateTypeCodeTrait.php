<?php

namespace App\Traits;

use App\Doctype;

trait GenerateTypeCodeTrait
{
    /**
     * Generate a unique type code based on the business rules
     */
    private function generateUniqueTypeCode($typeName)
    {
        // Clean and normalize the input
        $typeName = trim($typeName);
        $words = preg_split('/\s+/', $typeName);
        $words = array_filter($words); // Remove empty elements

        // Generate initial code based on single or multi-word
        if (count($words) == 1) {
            $baseCode = $this->generateSingleWordCode($words[0]);
        } else {
            $baseCode = $this->generateMultiWordCode($words);
        }

        // Ensure uniqueness
        $finalCode = $this->ensureUniqueness($baseCode, $typeName, $words);

        // Ensure length constraints (2-5 characters)
        if (strlen($finalCode) < 2) {
            $finalCode = $this->padCodeToMinLength($finalCode, $typeName);
        } elseif (strlen($finalCode) > 5) {
            $finalCode = substr($finalCode, 0, 5);
            // Re-check uniqueness after truncation
            $finalCode = $this->ensureUniqueness($finalCode, $typeName, $words);
        }

        return strtoupper($finalCode);
    }

    /**
     * Generate code for single-word types
     */
    private function generateSingleWordCode($word)
    {
        $word = strtolower($word);

        // Start with first 3 letters
        $code = substr($word, 0, 3);

        // If word is longer than 3 characters and we need more uniqueness
        if (strlen($word) > 3) {
            // Get remaining characters for potential expansion
            $remainingChars = substr($word, 3);

            // Add consonants first, then vowels
            $consonants = '';
            $vowels = '';

            for ($i = 0; $i < strlen($remainingChars); $i++) {
                $char = $remainingChars[$i];
                if (in_array($char, ['a', 'e', 'i', 'o', 'u'])) {
                    $vowels .= $char;
                } else {
                    $consonants .= $char;
                }
            }

            // Store for later use in conflict resolution
            $this->availableChars = $consonants . $vowels;
        }

        return $code;
    }

    /**
     * Generate code for multi-word types
     */
    private function generateMultiWordCode($words)
    {
        $code = '';

        // Take first letter of each word
        foreach ($words as $word) {
            $code .= strtolower($word[0]);
        }

        return $code;
    }

    /**
     * Ensure the code is unique in the database
     */
    private function ensureUniqueness($baseCode, $typeName, $words)
    {
        $code = $baseCode;
        $attempt = 0;
        $maxAttempts = 100; // Prevent infinite loops

        while ($this->codeExists($code) && $attempt < $maxAttempts) {
            $attempt++;

            if (count($words) == 1) {
                // Single word conflict resolution
                $code = $this->resolveSingleWordConflict($baseCode, $words[0], $attempt);
            } else {
                // Multi-word conflict resolution
                $code = $this->resolveMultiWordConflict($baseCode, $words, $attempt);
            }
        }

        // If still not unique after all attempts, add numeric suffix
//        if ($this->codeExists($code)) {
//            $counter = 1;
//            $originalCode = strlen($code) > 4 ? substr($code, 0, 4) : $code;
//
//            do {
//                $code = $originalCode . $counter;
//                $counter++;
//            } while ($this->codeExists($code) && strlen($code) <= 5);
//        }

        return $code;
    }

    /**
     * Resolve conflicts for single-word types
     */
    private function resolveSingleWordConflict($baseCode, $word, $attempt)
    {
        $word = strtolower($word);

        // Get characters beyond the base code
        $additionalChars = substr($word, strlen($baseCode));

        if (strlen($additionalChars) > 0) {
            // Separate consonants and vowels
            $consonants = '';
            $vowels = '';

            for ($i = 0; $i < strlen($additionalChars); $i++) {
                $char = $additionalChars[$i];
                if (in_array($char, ['a', 'e', 'i', 'o', 'u'])) {
                    $vowels .= $char;
                } else {
                    $consonants .= $char;
                }
            }

            // First try consonants, then vowels
            $availableChars = $consonants . $vowels;

            if ($attempt <= strlen($availableChars)) {
                return $baseCode . $availableChars[$attempt - 1];
            }
        }

        // If no more characters available, add numeric suffix
        return $baseCode . $attempt;
    }

    /**
     * Resolve conflicts for multi-word types
     */
    private function resolveMultiWordConflict($baseCode, $words, $attempt)
    {
        // Case A: First word differs - add consonant after first initial
        // Case B: First word same, second word differs - add consonant after second initial
        // Case C: Both words used - continue with more consonants

        $existingCodes = $this->getExistingCodes();

        // Analyze existing conflicts to determine strategy
        $conflictType = $this->analyzeConflictType($baseCode, $words, $existingCodes);

        switch ($conflictType) {
            case 'first_word_differs':
                return $this->handleFirstWordDiffers($baseCode, $words, $attempt);

            case 'second_word_differs':
                return $this->handleSecondWordDiffers($baseCode, $words, $attempt);

            case 'both_words_used':
                return $this->handleBothWordsUsed($baseCode, $words, $attempt);

            default:
                // Fallback: add consonants from second word
                return $this->addConsonantsFromSecondWord($baseCode, $words, $attempt);
        }
    }

    /**
     * Handle Case A: First word differs
     */
    private function handleFirstWordDiffers($baseCode, $words, $attempt)
    {
        $firstWord = strtolower($words[0]);
        $consonants = $this->getConsonants($firstWord, 1); // Skip first letter

        if ($attempt <= strlen($consonants)) {
            $insertPos = 1; // After first initial
            return substr($baseCode, 0, $insertPos) . $consonants[$attempt - 1] . substr($baseCode, $insertPos);
        }

        return $baseCode . $attempt;
    }

    /**
     * Handle Case B: Second word differs
     */
    private function handleSecondWordDiffers($baseCode, $words, $attempt)
    {
        if (count($words) < 2) {
            return $baseCode . $attempt;
        }

        $secondWord = strtolower($words[1]);
        $consonants = $this->getConsonants($secondWord, 1); // Skip first letter

        if ($attempt <= strlen($consonants)) {
            $insertPos = 2; // After second initial
            if (strlen($baseCode) >= 2) {
                return substr($baseCode, 0, $insertPos) . $consonants[$attempt - 1] . substr($baseCode, $insertPos);
            }
        }

        return $baseCode . $attempt;
    }

    /**
     * Handle Case C: Both words already used
     */
    private function handleBothWordsUsed($baseCode, $words, $attempt)
    {
        return $this->addConsonantsFromSecondWord($baseCode, $words, $attempt);
    }

    /**
     * Add consonants from second word
     */
    private function addConsonantsFromSecondWord($baseCode, $words, $attempt)
    {
        if (count($words) < 2) {
            return $baseCode . $attempt;
        }

        $secondWord = strtolower($words[1]);
        $consonants = $this->getConsonants($secondWord, 1);

        if ($attempt <= strlen($consonants)) {
            return $baseCode . $consonants[$attempt - 1];
        }

        return $baseCode . $attempt;
    }

    /**
     * Get consonants from a word, optionally skipping first N characters
     */
    private function getConsonants($word, $skip = 0)
    {
        $consonants = '';
        $vowels = ['a', 'e', 'i', 'o', 'u'];

        for ($i = $skip; $i < strlen($word); $i++) {
            $char = strtolower($word[$i]);
            if (ctype_alpha($char) && !in_array($char, $vowels)) {
                $consonants .= $char;
            }
        }

        return $consonants;
    }

    /**
     * Analyze the type of conflict for multi-word types
     */
    private function analyzeConflictType($baseCode, $words, $existingCodes)
    {
        // This is a simplified analysis - in a full implementation,
        // you would analyze existing codes to determine the best strategy

        if (count($words) >= 2) {
            return 'second_word_differs';
        }

        return 'first_word_differs';
    }

    /**
     * Get all existing type codes from database
     */
    private function getExistingCodes()
    {
        return Doctype::pluck('type_code')->filter()->toArray();
    }

    /**
     * Check if a code already exists in the database
     */
    private function codeExists($code)
    {
        return Doctype::where('type_code', strtoupper($code))->exists();
    }

    /**
     * Pad code to minimum length if needed
     */
    private function padCodeToMinLength($code, $typeName)
    {
        if (strlen($code) >= 2) {
            return $code;
        }

        // If code is too short, try to add more characters from the type name
        $cleanName = preg_replace('/[^a-zA-Z]/', '', $typeName);

        if (strlen($cleanName) > strlen($code)) {
            $additional = substr($cleanName, strlen($code), 2 - strlen($code));
            $code .= $additional;
        }

        // If still too short, pad with 'X'
        while (strlen($code) < 2) {
            $code .= 'X';
        }

        return $code;
    }
}
