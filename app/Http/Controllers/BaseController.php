<?php

namespace App\Http\Controllers;

use App\Corrective;
use App\HseIssue;
use App\Issue;
use App\Nearmiss;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class BaseController extends Controller
{

    public function getProfilePic($id, $modelname)
    {
        switch ($modelname) {
            case "issue":
                $issue = Issue::with('postedBy', 'approvedBy', 'closedBy')->where('id', $id)->first();
                break;
            case "corrective":
                $issue = Corrective::where('id', $id)->first();
                break;
            case "hseissues":
                $issue = HseIssue::with(['hseCategory','departmentHse'])->where('id', $id)->first();
                break;
            case "nearmiss":
                $issue = Nearmiss::where('id', $id)->first();
                break;
        }
        $responsibleUser = User::where('email', $issue->responsibility)->first();
        $postedByUser = User::where('employeeid', $issue->postedby)->first();
        $issue->postedByUser = $postedByUser;
        $issue->reviewedBy = $issue->reviewedBy ? $issue->reviewedBy->name : '';
        $issue->dept = $issue->dept ? $issue->dept->departmentname : '';
        $issue->responsibleUser = $responsibleUser;
        if ($modelname == 'issue') {
            $issue->created_at2 = $issue->created_at;
            $issue->updated_at2 = $issue->updated_at;
        } else if ($modelname == 'hseissues') {
            $hsetypes = DB::table('hsetypes')->pluck('hsetype','id')->toArray();
            $issue->type = isset($hsetypes[$issue->type]) ? $hsetypes[$issue->type] : '';
            $issue->division = $issue->division ? $issue->division->name : '';
            $issue->subdiv = $issue->subdiv;
            $issue->department = $issue->departmentHse ? $issue->departmentHse->departmentname : $issue->department;
        }

        return response()->json(['data' => $issue]);
    }
    public function showFileUpload()
    {
        return view('showFile');
    }
    public function fileUpload(Request $req)
    {
        $req->validate([
            'file' => 'required|max:2048',
        ]);
        if ($req->file()) {
            $fileName = time() . '_' . $req->file->getClientOriginalName();
            // save file to azure blob virtual directory uplaods in your container
            $filePath = $req->file('file')->storeAs('uploads/', $fileName, 'azure');
            Session::flash('msg', "File uploaded successfully");
            return redirect()->back();
        }
    }
    public static function calculateStatus($score_of_all): string
    {
        $status = null;
        if ($score_of_all >= 0 && $score_of_all <= 39) {
            $status = 'poor';
        } else if ($score_of_all > 39 && $score_of_all <= 59) {
            $status = 'below_average';
        } else if ($score_of_all > 59 && $score_of_all <= 74) {
            $status = 'fair';
        } else if ($score_of_all > 74 && $score_of_all <= 89) {
            $status = 'good';
        } else if ($score_of_all > 89 && $score_of_all <= 100) {
            $status = 'excellent';
        }

        return $status;
    }

    public static function calculateWholeScore($score, $qstn): array
    {
        $score_of_all = 0;
        $whole_score = 0;
        for ($i = 0; $i < count($score); $i++) {
            if (isset($qstn[$i]) && $qstn[$i] != null && $qstn[$i] != 5 && $qstn[$i] != 10 && $qstn[$i] != 15) { //remove N/A values
                $score_of_all += $qstn[$i] * $score[$i];
                $whole_score += $score[$i];
            }
        }
        return [$score_of_all, $whole_score];
    }

    public static function addSlashToEndOfURL()
    {
        $app_url = config('constants.app_url');
        $last_app_url_char = substr($app_url, -1);

        return $last_app_url_char != '/' ? $app_url . '/' : $app_url;
    }

    public static function getCompanyFromAppRUL()
    {
        $app_url = BaseController::addSlashToEndOfURL();
        $replace_slash = str_replace('/', '', explode('://', $app_url));
        $company = ucfirst(explode('.', $replace_slash[1])[0]);
        
        return $company;
    }

    /*
    Check if an array has no null or space elements
     */
    public static function containsOnlyNull($input)
    {
        return empty(array_filter($input, function ($a) {return $a !== null && !(strlen($a) && strlen(trim($a)) == 0);}));
    }

}
