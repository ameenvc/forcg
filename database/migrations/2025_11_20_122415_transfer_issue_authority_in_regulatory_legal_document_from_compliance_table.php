<?php

use Illuminate\Database\Migrations\Migration;
use App\Compliance;
use App\ComplianceLegalDocument;
use App\RegulatoryAuthority;

class TransferIssueAuthorityInRegulatoryLegalDocumentFromComplianceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $compliances = Compliance::select(['Cid','issue','authority','updated_at','created_at'])->get();
        foreach($compliances as $compliance){
            $exists = RegulatoryAuthority::where('name',$compliance->authority)->whereNull('deleted_at')->first();
            if(!$exists){
                $regulatory = new RegulatoryAuthority();
                $regulatory->timestamps = false; 

                $regulatory->name = $compliance->authority;
                $regulatory->initial_name = $compliance->authority;
                $regulatory->status = 'Approved';

                $regulatory->created_at = $compliance->created_at;
                $regulatory->updated_at = $compliance->created_at;

                $regulatory->save();

                Compliance::where('Cid',$compliance->Cid)->update([
                    'updated_at'=>$compliance->updated_at,
                    'authority'=>$regulatory->id
                ]);
            }else{
                Compliance::where('Cid',$compliance->Cid)->update([
                    'updated_at'=>$compliance->updated_at,
                    'authority'=>$exists->id
                ]);
            }
        }
        $regulatories = RegulatoryAuthority::whereNull('deleted_at')->get();
        foreach($regulatories as $regulatory){
            $compliances = Compliance::where('authority',$regulatory->id)->select(['Cid','issue','updated_at','created_at','authority'])->get();
            foreach($compliances as $compliance){
                $exists = ComplianceLegalDocument::where('RAC_id',$regulatory->id)->where('name',$compliance->issue)->whereNull('deleted_at')->first();
                if(!$exists){
                    $doc = new ComplianceLegalDocument();
                    $doc->timestamps = false;

                    $doc->name = $compliance->issue;
                    $doc->RAC_id = $regulatory->id;
                    $doc->status = 'Approved';

                    $doc->created_at = $compliance->created_at;
                    $doc->updated_at = $compliance->created_at;

                    $doc->save();

                    Compliance::where('Cid',$compliance->Cid)->update([
                        'updated_at'=>$compliance->updated_at,
                        'issue'=>$doc->id
                    ]);
                }else{
                    Compliance::where('Cid',$compliance->Cid)->update([
                        'updated_at'=>$compliance->updated_at,
                        'issue'=>$exists->id
                    ]);
                }
            }
        }
        foreach($regulatories as $regulatory){
            $count = ComplianceLegalDocument::where('RAC_id',$regulatory->id)->count();
            $regulatory->timestamps = false;
            $regulatory->no_of_documents = $regulatory->no_of_documents + $count;
            $regulatory->save();
        }
    }
}
