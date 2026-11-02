<?php

namespace App\Http\Controllers\Admin\Complaint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Helpers\Complaint\ComplaintFormConfigurationHelper;
use App\Models\SourceOfIssue;

class SourceOfIssueController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'mfa']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = DB::table('modenadisable')->First();
        $sourceOfIssues = SourceOfIssue::get();

        return view('admin.complaint.source_of_issue')
            ->with('model', $models)
            ->with('sourceOfIssues',$sourceOfIssues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|unique:source_of_issues,title,NULL,id,deleted_at,NULL',
        ],[
            'title.required' => 'The '.ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel().' Title field is required.',
            'title.unique' => 'A '.ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel().' with the same name already exists.',
        ]);

        $models = DB::table('modenadisable')->First();
        if ($validator->fails()){
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('model', $models);
        } else {
            SourceOfIssue::create([
                'title' => $request->title,
                'updated_by' => Auth::user()->id
            ]);
            Session::flash('msg','Complaint '.ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel().' Name has been added successfully.');
            return redirect()->back()->with('model',$models);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'string',
                Rule::unique('source_of_issues', 'title')
                    ->ignore($id, 'id')
                    ->where(function ($query) use ($request) {
                        return $query->where('deleted_at', null);
                    })
            ],
        ],[
            'title.required' => 'The '.ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel().' title field is required.',
            'title.unique' => 'A '.ComplaintFormConfigurationHelper::getSourceOfIssueDisplayLabel().' with the same title already exists.',
        ]);
        if ($validator->fails()) {
            return [
                'error' => $validator->errors()->all(),
            ];
        }
        $error = null;
        if ($error == null) {
            SourceOfIssue::where('id',$id)->update([
                'title' => $request->title,
                'updated_by' => Auth::user()->id
            ]);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = null;
        if ($error == null) {
            SourceOfIssue::destroy($id);
            return [
                'error' => $error,
            ];
        }
        return [
            'error' => $error,
        ];
    }
}
