<?php

namespace App\Console\Commands;

use App\Doctype;
use App\GovernanceHub;
use App\GovernanceHubDraft;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Exception;

class AddDocumentTypeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:document-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add document types to the doctype table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->info('Starting to add document types...');
            
            $missingDocumentTypes = $this->getMissingDocumentTypes();
            
            if ($missingDocumentTypes->isEmpty()) {
                $this->info('No new document types to add.');
            } else {
                $this->addMissingDocumentTypes($missingDocumentTypes);
                $this->info('Document types added successfully.');
            }
            
            // Correct existing document types in governance documents
            $this->correctDocumentTypeForDocument();
            $this->info('Document types corrected in existing documents.');

            $this->info('DONE');
            
            return 0;
        } catch (Exception $e) {
            $this->error('An error occurred while processing document types: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Get document types that exist in governance documents but not in the master list.
     *
     * @return Collection
     */
    private function getMissingDocumentTypes(): Collection
    {
        $governanceDocumentTypes = $this->getGovernanceDocumentTypes();
        $masterDocumentTypes = $this->getMasterDocumentTypes();
        
        // Normalize governance types and remove duplicates
        $normalizedGovernanceTypes = $governanceDocumentTypes
            ->map(function ($type) {
                return $this->normalizeDocumentType($type);
            })
            ->unique();
        
        // Normalize master types for comparison
        $normalizedMasterTypes = $masterDocumentTypes
            ->map(function ($type) {
                return $this->normalizeDocumentType($type);
            });
        
        // Find governance types that don't exist in master list
        return $normalizedGovernanceTypes->filter(function ($normalizedType) use ($normalizedMasterTypes) {
            return !$normalizedMasterTypes->contains($normalizedType);
        });
    }

    /**
     * Get all document types from governance hub and draft documents.
     *
     * @return Collection
     */
    private function getGovernanceDocumentTypes(): Collection
    {
        $governanceHubTypes = GovernanceHub::select('type')
            ->groupBy('type')
            ->pluck('type');
            
        $governanceDraftTypes = GovernanceHubDraft::select('type')
            ->groupBy('type')
            ->pluck('type');
            
        return $governanceHubTypes->merge($governanceDraftTypes)->unique();
    }

    /**
     * Get all document types from the master doctype table.
     *
     * @return Collection
     */
    private function getMasterDocumentTypes(): Collection
    {
        return Doctype::select('typename')
            ->groupBy('typename')
            ->pluck('typename');
    }

    /**
     * Normalize document type for comparison.
     *
     * @param string $documentType
     * @return string
     */
    private function normalizeDocumentType(string $documentType): string
    {
        if ($documentType == 'work_instuctions') return 'Work Instructions';

        return str_replace(' ', '_', strtolower($documentType));
    }

    /**
     * Add missing document types to the doctype table.
     *
     * @param Collection $missingDocumentTypes
     * @return void
     */
    private function addMissingDocumentTypes(Collection $missingDocumentTypes): void
    {
        // Convert normalized types back to proper case for storage
        $documentTypeData = $missingDocumentTypes->map(function ($normalizedType) {
            // Convert back to title case for storage
            $properCaseType = str_replace('_', ' ', ucwords($normalizedType));
            $this->info("Adding document type: '{$properCaseType}'");
            
            return [
                'typename' => $properCaseType,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        // Remove old document types that are already in the table
        Doctype::whereIn('typename', $missingDocumentTypes)
            ->delete();

        // Insert new document types
        Doctype::insert($documentTypeData);
        
        $this->info("Successfully added {$missingDocumentTypes->count()} document type(s).");
    }

    /**
     * Correct document type for existing documents.
     *
     * This method updates existing records in GovernanceHub and GovernanceHubDraft
     * to ensure the document type is consistent with the new naming convention.
     *
     * @return void
     */
    public function correctDocumentTypeForDocument()
    {
        Doctype::where('typename', 'Work instuctions')->delete();
        GovernanceHub::where('type', 'work_instuctions')
            ->update(['type' => 'work_instructions']);
        GovernanceHubDraft::where('type', 'work_instuctions')
            ->update(['type' => 'work_instructions']);
    }
}
