<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UpdateTypeCodeForDocumentType extends Migration
{
    use \App\Traits\GenerateTypeCodeTrait;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $doctypes = DB::table('doctypes')->orderBy('level')->get();

        foreach ($doctypes as $doctype) {
            if (!empty($doctype->type_code)) {
                continue; // Skip if type_code is already set
            }

            $newCode = $this->generateUniqueTypeCode($doctype->typename);
            DB::table('doctypes')
                ->where('id', $doctype->id)
                ->update(['type_code' => $newCode]);
            Log::info("Updated Doctype type {$doctype->typename} with Type Code: {$newCode}");
        }

        return 0;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('doctypes')
            ->where('type_code', '!=', null)
            ->update(['type_code' => null]);
    }
}
