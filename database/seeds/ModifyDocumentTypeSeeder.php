<?php

use Illuminate\Database\Seeder;
use App\Doctype;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ModifyDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Add document type default
        $this->addDocumentTypeDefault();

        // 2. Transform type name to ID for governance objects
        $this->transformTypeNameToIdForGovernance();
    }

    /**
     * Add default document types if they do not exist.
     *
     * @return void
     */
    public function addDocumentTypeDefault()
    {
        $defaultType = config('constants.document_management.default_type');
        $docType = Doctype::all();
        $currentTime = now();

        // Case 1: Document type is empty
        if ($docType->isEmpty()) {
            Log::info('No document types found, adding default types.');
            $insertData = [];
            foreach ($defaultType as $index => $type) {
                $insertData[] = [
                    'typename'   => $type,
                    'is_default' => true,
                    'level' => $index + 1,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                ];
            }
            if (!empty($insertData)) {
                Doctype::insert($insertData);
            }

            Log::info('Default document types added successfully.');
        } else {
            // Case 2: Document type is not empty
            Log::info('Document types already exist, checking for default types.');
            Doctype::whereIn('typename', $defaultType)->update([
                'is_default' => true,
                'updated_at' => $currentTime,
            ]);
            Doctype::whereNotIn('typename', $defaultType)->update([
                'is_default' => false,
                'updated_at' => $currentTime,
            ]);
            $defaultTypeInDatabase = Doctype::whereIn('typename', $defaultType)->pluck('typename')->toArray();
            $insertData = [];
            foreach ($defaultType as $type) {
                if (!in_array($type, $defaultTypeInDatabase)) {
                    $insertData[] = [
                        'typename'   => $type,
                        'is_default' => true,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                    ];
                }
            }
            if (!empty($insertData)) {
                Doctype::insert($insertData);
            }

            // Update level for existing document types
            foreach ($defaultType as $index => $type) {
                Doctype::where('typename', $type)->update([
                    'level' => $index + 1,
                    'updated_at' => $currentTime,
                ]);
            }
            $nextLevel = count($defaultType) + 1;
            $isNotDefault = Doctype::where('is_default', false)->get();
            if ($isNotDefault->isNotEmpty()) {
                $isNotDefault->each(function ($item, $index) use ($currentTime, $nextLevel) {
                    $item->update([
                        'level' => $nextLevel + $index,
                        'updated_at' => $currentTime,
                    ]);
                });
            }
        }
    }

    /**
     * Transform type names to IDs for governance objects.
     *
     * @return void
     */
    public function transformTypeNameToIdForGovernance()
    {
        $docTypeAfterTransform = $this->getDocTypeAfterTransform();
        // governance_hubs
        $this->addTypeIdToGovernanceObject('governance_hubs', $docTypeAfterTransform);
        // governance_hub_drafts
        $this->addTypeIdToGovernanceObject('governance_hub_drafts', $docTypeAfterTransform);
        // governance_hub_draft_contents
        $this->addTypeIdToGovernanceObject('governance_hub_draft_contents', $docTypeAfterTransform);
        // governance_hub_document_contents
        $this->addTypeIdToGovernanceObject('governance_hub_document_contents', $docTypeAfterTransform);
    }

    /**
     * Add document type ID to governance objects based on the transformed type name.
     *
     * @param string $table
     * @param array $docTypeAfterTransform
     * @param string $column
     * @return void
     */
    public function addTypeIdToGovernanceObject(string $table, array $docTypeAfterTransform, string $column = 'type')
    {
        $listOfData = DB::table($table)->get();
        foreach ($listOfData as $item) {
            if (empty($item->{$column})) {
                Log::info("Skipping item with ID {$item->id} in table {$table} due to missing or empty '{$column}' column.");
                continue;
            }
            $typeAfterTransforming = $this->transformTypeName($item->{$column});
            if (in_array($typeAfterTransforming, array_keys($docTypeAfterTransform))) {
                DB::table($table)
                    ->where('id', $item->id)
                    ->update([
                        'doctype_id' => $docTypeAfterTransform[$typeAfterTransforming],
                        'updated_at' => now(),
                    ]);
            } else {
                // If the transformed type does not exist in doctypes, set to null
                DB::table($table)
                    ->where('id', $item->id)
                    ->update([
                        'doctype_id' => null,
                        'updated_at' => now(),
                    ]);
                Log::info("Type '{$typeAfterTransforming}' not found in doctypes, setting doctype_id to null for ID {$item->id}");
            }
        }
    }

    /**
     * Get the mapping of transformed type names to their corresponding document type IDs.
     *
     * @return array
     */
    private function getDocTypeAfterTransform(): array
    {
        $docTypes = DB::table('doctypes')->pluck('id', 'typename')->toArray();
        $docTypeAfterTransform = [];
        foreach ($docTypes as $typename => $id) {
            $docTypeAfterTransform[$this->transformTypeName($typename)] = $id;
        }
        $docTypeAfterTransform['work_instructions'] = $docTypeAfterTransform['work_instruction']; // Handle specific case for 'work_instructions'
        $docTypeAfterTransform['work_instuctions'] = $docTypeAfterTransform['work_instruction']; // Handle specific case for 'work_instuctions'

        return $docTypeAfterTransform;
    }

    /**
     * Transform the type name to a format suitable for the database.
     *
     * @param string $typeName
     * @return string
     */
    private function transformTypeName(string $typeName): string
    {
        return strtolower(str_replace(' ', '_', $typeName));
    }
}
