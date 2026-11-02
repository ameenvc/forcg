<?php

namespace App\Http\Controllers\Admin\Complaint;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComplaintTemplate;
use App\Models\ComplaintTemplateSection;
use App\Models\ComplaintTemplateSectionField;
use App\Models\ComplaintTemplateSectionFieldHistory;
use App\Product;
use App\ComplaintCategory;
use App\Type;
use App\ComplaintPlant;
use App\ComplaintUnit;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ComplaintFormConfigurationController extends Controller
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
        $model = DB::table('modenadisable')->First();
        // $complaintTemplates = ComplaintTemplate::with('complaintTemplateSections.complaintTemplateSectionFields.complaintTemplateSectionFieldHistories.user')->get();
        $complaintTemplates = ComplaintTemplate::with([
            'complaintTemplateSections.complaintTemplateSectionFields.complaintTemplateSectionFieldHistories.user',
            'complaintTemplateSections.complaintTemplateSectionFields.complaintTemplateSectionFieldMetas'
        ])->get();

        $complaintTemplates = $complaintTemplates->map(function ($template) {
            $template->complaintTemplateSections = $template->complaintTemplateSections->map(function ($section) {

                $section->complaintTemplateSectionFields = $section->complaintTemplateSectionFields->map(function ($field) {

                    // Convert field to array
                    $fieldArray = $field->toArray();

                    // Extract metas: [meta_key => meta_value]
                    $meta = collect($fieldArray['complaint_template_section_field_metas'] ?? [])
                        ->pluck('meta_value', 'meta_key');

                    // Merge meta into field
                    $merged = collect($fieldArray)
                        ->except('complaint_template_section_field_metas')
                        ->merge($meta);

                    return (object) $merged->toArray();
                });

                return $section;
            });

            return $template;
        });
        return view('admin.complaint.complaint_form_configuration', compact('model', 'complaintTemplates'));
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
        $templateConfig = $request->template_config;
        $dataComplaintTemplates = json_decode($templateConfig, true);

        foreach($dataComplaintTemplates as $dataComplaintTemplate) {

            ComplaintTemplate::where('id', $dataComplaintTemplate['id'])->update(['is_active' => $dataComplaintTemplate['is_active']]);

            foreach($dataComplaintTemplate['complaint_template_sections'] as $dataComplaintTemplateSection) {

                foreach($dataComplaintTemplateSection['complaint_template_section_fields'] as $dataComplaintTemplateSectionField) {
                    $complaintTemplateSectionField = ComplaintTemplateSectionField::find($dataComplaintTemplateSectionField['id']);

                    if( $complaintTemplateSectionField->enable_field != $dataComplaintTemplateSectionField['enable_field'] ||
                        $complaintTemplateSectionField->field_display_label != $dataComplaintTemplateSectionField['field_display_label'] ||
                        $complaintTemplateSectionField->field_is_mandatory != $dataComplaintTemplateSectionField['field_is_mandatory'])
                    {
                        ComplaintTemplateSectionField::where('id', $dataComplaintTemplateSectionField['id'])
                            ->update([
                                'enable_field' => $dataComplaintTemplateSectionField['enable_field'],
                                'field_display_label' => trim($dataComplaintTemplateSectionField['field_display_label']),
                                'field_is_mandatory' => $dataComplaintTemplateSectionField['field_is_mandatory']
                            ]);

                        if($complaintTemplateSectionField->field_display_label != $dataComplaintTemplateSectionField['field_display_label']) {
                            ComplaintTemplateSectionFieldHistory::create([
                                'complaint_template_section_field_id' => $dataComplaintTemplateSectionField['id'],
                                'previous_display_label' => $complaintTemplateSectionField->field_display_label,
                                'updated_display_label' => trim($dataComplaintTemplateSectionField['field_display_label']),
                                'updated_by' => Auth::guard('admin')->user()->user->id
                            ]);
                        }
                    }
                }
            }
        }
        $data = ComplaintTemplate::with('complaintTemplateSections.complaintTemplateSectionFields.complaintTemplateSectionFieldHistories.user')->get();
        return response()->json([
                'status' => true,
                'message' => 'Complaint form template configuration completed succesfully',
                'data' => $data
            ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
