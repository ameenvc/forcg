<?php

namespace App;
use App\User;
use Illuminate\Database\Eloquent\Model;

class RacmReviewerDetail extends Model
{
    protected $table = "racm_reviewer_details";
    protected $fillable = ['racm_id','reviewer_id',
                           'reviewed_status','reviewed_date',
                           'racm_rejected','racm_reject_reason',
                          ];

    public function reviewerdetails()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
    public function racmdetails()
    {
        return $this->belongsTo(RiskRacm::class, 'racm_id','id');
    }
}
