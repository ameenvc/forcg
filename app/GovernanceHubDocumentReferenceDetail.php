<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GovernanceHubDocumentReferenceDetail extends Model
{
    protected $fillable = ['document_id','reference_doc_number','reference_doc_name'];
}
