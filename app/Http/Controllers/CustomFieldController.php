<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\DropdownOption;
use App\ModuleBladePageDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * CustomFieldController handles the management of custom fields in the application.
 * It provides methods to display, create, update, and delete custom fields.
 */

class CustomFieldController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $fieldtypes = [
        'text' => 'Text',
        'number' => 'Number',
        'email' => 'Email',
        'date' => 'Date',
        'textarea' => 'Text Area',
        'select' => 'Dropdown',
        'multiselect' => 'Dropdown (Multiple Selection)',
        'file' => 'File Upload',
        'file_multiple' => 'File Upload (Multiple File Upload)',
        'checkbox' => 'Checkbox',
        'radio' => 'Radio Buttons',
        'datetime-local' => 'Date and Time',
        'time' => 'Time',
        'month' => 'Month',
        'week' => 'Week',
        'password' => 'Password',
    ];
    private $modules = [
        'auditmanagement' => 'Audit Management',
        'calibration' => 'Calibration Management',
        'complaintmanagement' => 'Customer Complaint',
        'compliance' => 'Compliance Management',
        'contextmanagement' => 'Context Management',
        'correctiveaction' => 'NCR & Corrective Actions',
        'customer_satisfaction' => 'Customer Satisfaction',
        'documentmanagement' => 'Document Management',
        'elearning' => 'E-Learning',
        'emergencyresponse' => 'Emergency Response',
        'enviaspects' => 'Environmental Aspect',
        'hse' => 'HSE Observations',
        'hr' => 'Human Resource',
        'incidentreport' => 'Incident Report',
        'managementreview' => 'Management Review',
        'maintainance' => 'Maintenance Management',
        'nearmiss' => 'Near Miss',
        'objectives' => 'Objectives',
        'occhealth' => 'Occupational Health',
        'ohsrisk' => 'Health and Safety Risk',
        'opportunity' => 'Opportunity',
        'riskmanagement' => 'Enterprise Risk',
        'vendormanagement' => 'Vendor Management',
        'wastemanagement' => 'Waste Management',
    ];
    private $model;
    private $modulenames;
    public function __construct()
    {
        $this->middleware(['auth:web,admin', 'mfa']);
        $this->model = DB::table('modenadisable')->first();
        $this->modulenames = getModuleNames();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $enable = DB::table('customfield_enadisable')->first();
        $enable = $enable ? $enable->is_enable : false;
        if (!$enable) {
            session()->flash('msg', 'Custom Field feature is disabled.');
            return redirect('/user/home');
        }
        $currentUser = User::where('email', Auth::user()->email)->first();
        $form = $this->getCustomFieldForm();
        $table = $this->getCustomFieldTable();
        return view('admin.custom_field.custom_field_configuration',compact('currentUser','form','table'))
            ->with([
                'model' => $this->model,
                'modulesfilter' => implode(',',array_values($this->modules)),
                'fieldtypesfilter' => implode(',',array_values($this->fieldtypes)),
            ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $enable = DB::table('customfield_enadisable')->first();
        $enable = $enable ? $enable->is_enable : false;
        if (!$enable) {
            session()->flash('msg', 'Custom Field feature is disabled.');
            return redirect('/user/home');
        }
        // Validation: module_name and field_name must be unique together
        $validator = Validator::make($request->all(), [
            'modulename' => 'required',
            'fieldname' => [
            'required',
            function ($attribute, $value, $fail) use ($request) {
                $exists = CustomField::where('module_name', $request->input('modulename'))
                ->where('field_name', $value)
                ->where('deleted', false)
                ->exists();
                if ($exists) {
                    $fail('Custom Field with this Module and Field Name already exists.');
                }
            }
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.custom_field.custom_field_configuration')
            ->withErrors($validator)
            ->withInput();
        }
        $moduleName = $request->input('modulename');
        $fieldName = $request->input('fieldname');
        $fieldType = $request->input('fieldtype');
        $isRequired = $request->input('mandatory', true);
        $subModule = $request->input('sub_module',null);
        $fieldPlaceholder = $request->input('fieldplaceholder');
        $dropdownOptions = $request->input('dropdown_options',[]);
        $displayPages = $request->input('display_pages',[]);
        $targetPages = $request->input('target_pages',[]);
        $combineTargetDisplayPage = array_merge($displayPages, $targetPages);
        $all_field_orders = $request->input('all_field_orders');
        $all_field_orders = json_decode($all_field_orders, true);
        $migrationSuccess = true;
        $modelName = null;
        switch ($moduleName) {
            case 'auditmanagement':
                $modelName = 'AuditManagement';
                $migrationSuccess = $this->addColumnToTable('auditmanagement', $fieldName);
                break;
            case 'calibration':
                if ($subModule == 'external') {
                    $modelName = 'CalibrationExternal';
                    $migrationSuccess = $this->addColumnToTable('calibration_external', $fieldName);
                } elseif ($subModule == 'internal') {
                    $modelName = 'CalibrationInternal';
                    $migrationSuccess = $this->addColumnToTable('calibration_internal', $fieldName);
                }
                break;
            case 'complaintmanagement':
                $modelName = 'ComplaintManagement';
                $migrationSuccess = $this->addColumnToTable('complaintmanagement', $fieldName);
                break;
            case 'compliance':
                $modelName = 'Compliance';
                $migrationSuccess = $this->addColumnToTable('compliance', $fieldName);
                break;
            case 'contextmanagement':
                $modelName = 'ContextManagement';
                $migrationSuccess = $this->addColumnToTable('contextmanagement', $fieldName);
                break;
            case 'correctiveaction':
                $modelName = 'CorrectiveAction';
                $migrationSuccess = $this->addColumnToTable('correctiveaction', $fieldName);
                break;
            case 'customer_satisfaction':
                $modelName = 'CustomerSatisfaction';
                $migrationSuccess = $this->addColumnToTable('customer_satisfaction', $fieldName);
                break;
            case 'documentmanagement':
                $modelName = 'DocumentManagement';
                $migrationSuccess = $this->addColumnToTable('documentmanagement', $fieldName);
                break;
            case 'elearning':
                $modelName = 'Elearning';
                $migrationSuccess = $this->addColumnToTable('elearning', $fieldName);
                break;
            case 'emergencyresponse':
                $modelName = 'EmergencyResponse';
                $migrationSuccess = $this->addColumnToTable('emergencyresponse', $fieldName);
                break;
            case 'enviaspects':
                $modelName = 'Enviaspects';
                $migrationSuccess = $this->addColumnToTable('enviaspects', $fieldName);
                break;
            case 'hse':
                $modelName = 'Hse';
                $migrationSuccess = $this->addColumnToTable('hse', $fieldName);
                break;
            case 'hr':
                $modelName = 'Hr';
                $migrationSuccess = $this->addColumnToTable('hr', $fieldName);
                break;
            case 'incidentreport':
                $modelName = 'IncidentReport';
                $migrationSuccess = $this->addColumnToTable('incidentreport', $fieldName);
                break;
            case 'managementreview':
                $modelName = 'ManagementReview';
                $migrationSuccess = $this->addColumnToTable('managementreview', $fieldName);
                break;
            case 'maintainance':
                $modelName = 'Maintainance';
                $migrationSuccess = $this->addColumnToTable('maintainance', $fieldName);
                break;
            case 'nearmiss':
                $modelName = 'Nearmiss';
                $migrationSuccess = $this->addColumnToTable('nearmiss', $fieldName);
                break;
            case 'objectives':
                $modelName = 'Objectives';
                $migrationSuccess = $this->addColumnToTable('objectives', $fieldName);
                break;
            case 'occhealth':
                $modelName =  'Occhealth';
                $migrationSuccess = $this->addColumnToTable('occhealth', $fieldName);
                break;
            case 'ohsrisk':
                $modelName = 'Ohsrisk';
                $migrationSuccess = $this->addColumnToTable('ohsrisk', $fieldName);
                break;
            case 'opportunity':
                $modelName = 'Opportunity';
                $migrationSuccess = $this->addColumnToTable('opportunity', $fieldName);
                break;
            case 'riskmanagement':
                $modelName = 'RiskManagement';
                $migrationSuccess = $this->addColumnToTable('riskmanagement', $fieldName);
                break;
            case 'vendormanagement':
                $modelName = 'VendorManagement';
                $migrationSuccess = $this->addColumnToTable('vendormanagement', $fieldName);
                break;
            case 'wastemanagement':
                $modelName = 'WasteManagement';
                $migrationSuccess = $this->addColumnToTable('wastemanagement', $fieldName);
                break;
        }
        if ($migrationSuccess) {
            $field = CustomField::create([
                'module_name' => $moduleName,
                'field_name' => $fieldName,
                'field_placeholder' => $fieldPlaceholder,
                'field_type' => $fieldType,
                'mandatory' => $isRequired,
                'sub_module' => $subModule,
                'model_name' => $modelName,
                'target_pages' => json_encode($targetPages),
                'display_pages' => json_encode($displayPages),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (in_array($fieldType, ['select', 'multiselect', 'radio', 'checkbox']) && !empty($dropdownOptions)) {
                $optionsToInsert = [];
                $now = now();
                $dropdownOptions = array_unique($dropdownOptions);
                foreach ($dropdownOptions as $option) {
                    $optionsToInsert[] = [
                        'custom_field_id' => $field->id,
                        'option'          => $option,
                        'created_at'      => $now,
                        'updated_at'      => $now
                    ];
                }
                DropdownOption::insert($optionsToInsert);
            }
            $allTargetDisplayPages = ModuleBladePageDetail::whereIn('id', $combineTargetDisplayPage)
                ->pluck('field_index', 'id')
                ->toArray();
            foreach ($combineTargetDisplayPage as $pageId) {
                $oldIndex = !empty($allTargetDisplayPages[$pageId])
                    ? json_decode($allTargetDisplayPages[$pageId], true)
                    : [];
                $newIndexForPage = $all_field_orders[$pageId] ?? [];
                $newFieldKey = 'custom_' . $field->id;
                // If no new order was submitted, push new field index to the old order
                if (empty($newIndexForPage)) {
                    $nextIndex = max(array_values($oldIndex))+1;
                    $oldIndex[$newFieldKey] = (string)$nextIndex;
                    $finalIndex = $oldIndex;
                } else {
                    $oldIndex[$newFieldKey] = max($newIndexForPage);
                    $sortedIndex = [];
                    foreach ($newIndexForPage as $desiredValue) {
                        foreach ($oldIndex as $key => $value) {
                            if ((string)$value === (string)$desiredValue && !array_key_exists($key, $sortedIndex)) {
                                $sortedIndex[$key] = $value;
                                break;
                            }
                        }
                    }
                    $finalIndex = $sortedIndex;
                }
                // Save updated order
                ModuleBladePageDetail::where('id', $pageId)->update([
                    'field_index' => json_encode($finalIndex, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT),
                    'updated_at' => now()
                ]);
            }

        } else {
            session()->flash('error', 'Custom Field could not be added due to internal server error.');
            return redirect()->route('admin.custom_field.custom_field_configuration');
        }
        session()->flash('msg', 'Custom Field added successfully.');
        return redirect()->route('admin.custom_field.custom_field_configuration');
    }
    private function addColumnToTable($table, $column)
    {
        try {
            $column = Str::slug($column, '_');
            if (!Schema::hasColumn($table, $column)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($column) {
                    $tableBlueprint->longText($column)->nullable();
                });
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error adding column directly using Schema: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomField  $customField
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $field = CustomField::findOrFail($id);
        $query1 = ModuleBladePageDetail::where('module_name', $field->module_name)
            ->where('is_display_page', true);
        $query2 = ModuleBladePageDetail::where('module_name', $field->module_name)
            ->where('is_target_page', true);
        if($field->module_name=='calibration'){
            $query1 = $query1->where('sub_module',$field->sub_module);
            $query2 = $query2->where('sub_module',$field->sub_module);
        }
        $displayPages = $query1->orderBy('group_name')->get()->groupBy('group_name');
        $targetPages = $query2->orderBy('group_name')->get()->groupBy('group_name');
        $display = json_decode($field->display_pages);
        $target = json_decode($field->target_pages);
        $options = DropdownOption::where('custom_field_id',$id)->where('is_deleted',false)->pluck('option','id')->toArray();
        $view = view('admin.custom_field.display_target_page')
            ->with([
                'displayPages' => $displayPages,
                'targetPages' => $targetPages,
                'display' => $display,
                'target' => $target,
            ])->render();
        $editform = view('admin.custom_field.edit_custom_field_form')
                    ->with([
                        'modulenames' => $this->modulenames,
                        'modules' => $this->modules,
                        'fieldtypes' => $this->fieldtypes,
                        'field' => $field,
                        'view' => $view,
                        'options' => $options
                    ])->render();
        return [
            'editform' => $editform
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomField  $customField
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $enable = DB::table('customfield_enadisable')->first();
        $enable = $enable ? $enable->is_enable : false;
        if (!$enable) {
            session()->flash('msg', 'Custom Field feature is disabled.');
            return redirect('/user/home');
        }
        // Validation: module_name and field_name must be unique together, excluding current record
        $validator = Validator::make($request->all(), [
            'modulenameedit' => 'required',
            'fieldnameedit' => [
            'required',
            function ($attribute, $value, $fail) use ($request) {
                $exists = CustomField::where('module_name', $request->input('modulenameedit'))
                    ->where('field_name', $value)
                    ->where('deleted', false)
                    ->where('id', '!=', $request->input('id'))
                    ->exists();
                if ($exists) {
                    $fail('Custom Field with this Module and Field Name already exists.');
                }
            }
            ],
        ]);
        if ($validator->fails()) {
            return redirect()->route('admin.custom_field.custom_field_configuration')
            ->withErrors($validator)
            ->withInput();
        }
        $moduleName = $request->input('modulenameedit');
        $id = $request->input('id');
        $fieldName = $request->input('fieldnameedit');
        $fieldType = $request->input('fieldtypeedit');
        $isRequired = $request->input('mandatoryedit', true);
        $subModule = $request->input('sub_moduleedit',null);
        $fieldPlaceholder = $request->input('fieldplaceholderedit');
        $dropdownOptions1 = $request->input('dropdown_options_edit',[]);
        $dropdownOptions2 = $request->input('dropdown_options_edit1',[]);
        $dropDownOptionIds = $request->input('dropdown_ids');
        $displayPages = $request->input('display_pages',[]);
        $targetPages = $request->input('target_pages',[]);
        $field = CustomField::with('dropdownOption')->findOrFail($id);

        $combineTargetDisplayPage = array_merge($displayPages, $targetPages);
        $all_field_orders = $request->input('all_field_orders');
        $all_field_orders = json_decode($all_field_orders, true);
        $now = now();
        $migrationSuccess = true;
        $modelName = null;
        if($subModule){
            if($subModule=='external'){
                $modelName = 'CalibrationExternal';
                $isSubModuleChanged = $field->sub_module !== $subModule;
                $parameters = [
                    'old_column' => Str::slug($field->field_name, '_'),
                    'new_column' => Str::slug($fieldName, '_'),
                    'table_name' => 'calibration_external',
                    'old_table_name' => 'calibration_internal',
                    'is_sub_module_changed' => $isSubModuleChanged,
                ];
                $migrationSuccess = $this->editColumnInTable($parameters);
            }elseif($subModule=='internal'){
                $modelName = 'CalibrationInternal';
                $isSubModuleChanged = $field->sub_module !== $subModule;
                $parameters = [
                    'old_column' => Str::slug($field->field_name, '_'),
                    'new_column' => Str::slug($fieldName, '_'),
                    'table_name' => 'calibration_internal',
                    'old_table_name' => 'calibration_external',
                    'is_sub_module_changed' => $isSubModuleChanged,
                ];
                $migrationSuccess = $this->editColumnInTable($parameters);
            }
        }else{

        }
        if($migrationSuccess){
            $field = CustomField::with('dropdownOption')->findOrFail($id);
            $allTargetDisplayPages = ModuleBladePageDetail::whereIn('id', $combineTargetDisplayPage)
                ->pluck('field_index', 'id')
                ->toArray();
            $newFieldKey = 'custom_' . $id;
            foreach ($combineTargetDisplayPage as $pageId) {
                $oldIndex = !empty($allTargetDisplayPages[$pageId])
                    ? json_decode($allTargetDisplayPages[$pageId], true)
                    : [];
                $newIndexForPage = $all_field_orders[$pageId] ?? [];
                // If no new order was submitted, push new field index to the old order
                if (empty($newIndexForPage)) {
                    // Check if $pageId is already present in either target_pages or display_pages for this field
                    $check = CustomField::where('id', $id)
                        ->where(function($query) use ($pageId) {
                            $query->whereJsonContains('target_pages', (string)$pageId)
                                  ->orWhereJsonContains('display_pages', (string)$pageId);
                        })
                        ->exists();
                    if ($check) {
                        // If already present, skip adding to $oldIndex
                        $finalIndex = $oldIndex;
                        continue;
                    }
                    $nextIndex = max(array_values($oldIndex))+1;
                    $oldIndex[$newFieldKey] = (string)$nextIndex;
                    $finalIndex = $oldIndex;
                } else {
                    if(!array_key_exists($newFieldKey, $oldIndex)) {
                        $oldIndex[$newFieldKey] = (string)max($newIndexForPage)+1;
                    }
                    $sortedIndex = [];
                    foreach ($newIndexForPage as $desiredValue) {
                        foreach ($oldIndex as $key => $value) {
                            if ((string)$value === (string)$desiredValue && !array_key_exists($key, $sortedIndex)) {
                                $sortedIndex[$key] = $value;
                                break;
                            }
                        }
                    }
                    $finalIndex = $sortedIndex;
                }
                // Save updated order
                ModuleBladePageDetail::where('id', $pageId)->update([
                    'field_index' => json_encode($finalIndex, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT),
                    'updated_at' => $now
                ]);
            }
            CustomField::where('id',$id)->update([
                'module_name' => $moduleName,
                'field_name' => $fieldName,
                'field_placeholder' => $fieldPlaceholder,
                'field_type' => $fieldType,
                'mandatory' => $isRequired,
                'sub_module' => $subModule,
                'model_name' => $modelName,
                'target_pages' => json_encode($targetPages),
                'display_pages' => json_encode($displayPages),
                'updated_at' => $now
            ]);

            $existingOptions = $field->dropdownOption ? $field->dropdownOption->pluck('id')->toArray() : [];
            $removedIds = array_diff($existingOptions, $dropDownOptionIds ?? []);
            if (!empty($removedIds)) {
                DropdownOption::whereIn('id', $removedIds)->update([
                    'is_deleted'  => true,
                    'updated_at'  => $now,
                ]);
            }
            if (!empty($dropDownOptionIds)) {
                foreach ($dropDownOptionIds as $index => $optionId) {
                    DropdownOption::where('id', $optionId)->update([
                        'option'      => $dropdownOptions1[$index] ?? '',
                        'updated_at'  => $now,
                    ]);
                }
            }
            if (!empty($dropdownOptions2)) {
                $insertData = [];
                foreach ($dropdownOptions2 as $option) {
                    $insertData[] = [
                        'custom_field_id' => $id,
                        'option'          => $option,
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ];
                }
                DropdownOption::insert($insertData);
            }
        }else {
            session()->flash('error', 'Custom Field could not be updated due to internal server error.');
            return redirect()->route('admin.custom_field.custom_field_configuration');
        }
        session()->flash('msg', 'Custom Field updated successfully');
        return redirect()->route('admin.custom_field.custom_field_configuration');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomField  $customField
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $enable = DB::table('customfield_enadisable')->first();
        $enable = $enable ? $enable->is_enable : false;
        if (!$enable) {
            session()->flash('msg', 'Custom Field feature is disabled.');
            return response()->json([
                'success' => false,
                'redirect' => url('/user/home'),
            ]);
        }
        try {
            $field = CustomField::findOrFail($id);
            $column = Str::slug($field->field_name, '_');
            $table = null;
            $model = null;
            switch($field->module_name){
                case 'calibration':
                    if ($field->sub_module == 'external') {
                    $table = 'calibration_external';
                    $model = 'CalibrationExternal';
                    } elseif ($field->sub_module == 'internal') {
                    $table = 'calibration_internal';
                    $model = 'CalibrationInternal';
                    }
                    break;
                case 'auditmanagement':
                    $table = 'auditmanagement';
                    $model = 'AuditManagement';
                    break;
                case 'complaintmanagement':
                    $table = 'complaintmanagement';
                    $model = 'ComplaintManagement';
                    break;
                case 'compliance':
                    $table = 'compliance';
                    $model = 'Compliance';
                    break;
                case 'contextmanagement':
                    $table = 'contextmanagement';
                    $model = 'ContextManagement';
                    break;
                case 'correctiveaction':
                    $table = 'correctiveaction';
                    $model = 'CorrectiveAction';
                    break;
                case 'customer_satisfaction':
                    $table = 'customer_satisfaction';
                    $model = 'CustomerSatisfaction';
                    break;
                case 'documentmanagement':
                    $table = 'documentmanagement';
                    $model = 'DocumentManagement';
                    break;
                case 'elearning':
                    $table = 'elearning';
                    $model = 'Elearning';
                    break;
                case 'emergencyresponse':
                    $table = 'emergencyresponse';
                    $model = 'EmergencyResponse';
                    break;
                case 'enviaspects':
                    $table = 'enviaspects';
                    $model = 'Enviaspects';
                    break;
                case 'hse':
                    $table = 'hse';
                    $model = 'Hse';
                    break;
                case 'hr':
                    $table = 'hr';
                    $model = 'Hr';
                    break;
                case 'incidentreport':
                    $table = 'incidentreport';
                    $model = 'IncidentReport';
                    break;
                case 'managementreview':
                    $table = 'managementreview';
                    $model = 'ManagementReview';
                    break;
                case 'maintainance':
                    $table = 'maintainance';
                    $model = 'Maintainance';
                    break;
                case 'nearmiss':
                    $table = 'nearmiss';
                    $model = 'Nearmiss';
                    break;
                case 'objectives':
                    $table = 'objectives';
                    $model = 'Objectives';
                    break;
                case 'occhealth':
                    $table = 'occhealth';
                    $model = 'Occhealth';
                    break;
                case 'ohsrisk':
                    $table = 'ohsrisk';
                    $model = 'Ohsrisk';
                    break;
                case 'opportunity':
                    $table = 'opportunity';
                    $model = 'Opportunity';
                    break;
                case 'riskmanagement':
                    $table = 'riskmanagement';
                    $model = 'RiskManagement';
                    break;
                case 'vendormanagement':
                    $table = 'vendormanagement';
                    $model = 'VendorManagement';
                    break;
                case 'wastemanagement':
                $table = 'wastemanagement';
                $model = 'WasteManagement';
                break;
            }
            if ($table && Schema::hasColumn($table, $column)) {
                $removeFieldKey = 'custom_'.$id;
                $targetPage = json_decode($field->target_pages);
                $displayPage = json_decode($field->display_pages);
                $combine = array_merge($targetPage,$displayPage);
                $allPages = ModuleBladePageDetail::whereIn('id',$combine)->select(['id','field_index'])->get();
                foreach($allPages as $page){
                    $fieldIndex = json_decode($page->field_index, true);
                    // Skip if field to be removed doesn't exist
                    if (!isset($fieldIndex[$removeFieldKey])) {
                        continue;
                    }
                    $removedValue = (int)$fieldIndex[$removeFieldKey];
                    // Remove the deleted field
                    unset($fieldIndex[$removeFieldKey]);
                    // Adjust only other `custom_` keys
                    foreach ($fieldIndex as $key => &$value) {
                        if (str_starts_with($key, 'custom_') && (int)$value > $removedValue) {
                            $value = (string)((int)$value - 1);
                        }
                    }
                    // Save updated index preserving key order
                    ModuleBladePageDetail::where('id', $page->id)->update([
                        'field_index' => json_encode($fieldIndex, JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT)
                    ]);
                }
                // If the column exists, remove it
                $hasData = DB::table($table)->whereNotNull($column)->limit(1)->exists();
                if ($hasData) {
                    $field->deleted = true;
                    $field->save();
                } else {
                    // Drop the column from the table
                    Schema::table($table, function ($tableBlueprint) use ($column) {
                        $tableBlueprint->dropColumn($column);
                    });
                    $field->delete();
                }
            } else {
                return response()->json(['success' => false,'message' => 'Field or column does not exist.']);
            }
            return response()->json(['success' => true,'message' => 'Custom Field deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Error deleting custom field: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Internal server error.']);
        }
    }
    //---------------------Custom Field Configuration Start-----------------------//

    /**
     * Display the custom fields.
     * @param string $moduleName
     * @return \Illuminate\View\View
     */
    private function getCustomFieldForm(){
        $form = view('admin.custom_field.custom_field_configuration_form')
                ->with([
                    'modulenames' => $this->modulenames,
                    'modules' => $this->modules,
                    'fieldtypes' => $this->fieldtypes,
                ])->render();
        return $form;
    }
    /**
     * Generate the custom field configuration table.
     * @return string Rendered HTML table
     */
    private function getCustomFieldTable(){
        $custom_fields = CustomField::orderBy('created_at', 'desc')->get();
        foreach($custom_fields as $field){
            switch($field->module_name) {
                case 'calibration':
                    $field->hasData = $field->sub_module == 'external'
                        ? DB::table('calibration_external')->whereNotNull(Str::slug($field->field_name, '_'))->exists()
                        : DB::table('calibration_internal')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'auditmanagement':
                    $field->hasData = DB::table('auditmanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'complaintmanagement':
                    $field->hasData = DB::table('complaintmanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'compliance':
                    $field->hasData = DB::table('compliance')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'contextmanagement':
                    $field->hasData = DB::table('contextmanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'correctiveaction':
                    $field->hasData = DB::table('correctiveaction')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'customer_satisfaction':
                    $field->hasData = DB::table('customer_satisfaction')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'documentmanagement':
                    $field->hasData = DB::table('documentmanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'elearning':
                    $field->hasData = DB::table('elearning')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'emergencyresponse':
                    $field->hasData = DB::table('emergencyresponse')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'enviaspects':
                    $field->hasData = DB::table('enviaspects')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'hse':
                    $field->hasData = DB::table('hse')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'hr':
                    $field->hasData = DB::table('hr')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'incidentreport':
                    $field->hasData = DB::table('incidentreport')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'managementreview':
                    $field->hasData = DB::table('managementreview')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'maintainance':
                    $field->hasData = DB::table('maintainance')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'nearmiss':
                    $field->hasData = DB::table('nearmiss')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'objectives':
                    $field->hasData = DB::table('objectives')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'occhealth':
                    $field->hasData = DB::table('occhealth')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'ohsrisk':
                    $field->hasData = DB::table('ohsrisk')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'opportunity':
                    $field->hasData = DB::table('opportunity')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'riskmanagement':
                    $field->hasData = DB::table('riskmanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'vendormanagement':
                    $field->hasData = DB::table('vendormanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
                case 'wastemanagement':
                    $field->hasData = DB::table('wastemanagement')->whereNotNull(Str::slug($field->field_name, '_'))->exists();
                    break;
            }
        }

        $count = 1;
        $table = view('admin.custom_field.custom_field_configuration_table')
                ->with([
                    'modulenames' => $this->modulenames,
                    'modules' => $this->modules,
                    'fieldtypes' => $this->fieldtypes,
                    'custom_fields' => $custom_fields,
                    'count' => $count
                ])->render();
        return $table;
    }
    /**
     * Get the target display page for a module.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function getTargetDisplayPage(Request $request){
        $moduleName = $request->modulename;
        $subModule = $request->sub_module;
        $query1 = ModuleBladePageDetail::where('module_name', $moduleName)
            ->where('is_display_page', true);
        $query2 = ModuleBladePageDetail::where('module_name', $moduleName)
            ->where('is_target_page', true);
        if($moduleName=='calibration'){
            $query1 = $query1->where('sub_module',$subModule);
            $query2 = $query2->where('sub_module',$subModule);
        }
        $displayPages = $query1->orderBy('group_name')->get()->groupBy('group_name');
        $targetPages = $query2->orderBy('group_name')->get()->groupBy('group_name');
        $view = view('admin.custom_field.display_target_page')
            ->with([
                'displayPages' => $displayPages,
                'targetPages' => $targetPages,
            ])->render();

        return [
            'view' => $view,
            'module_name' => $moduleName,
        ];

    }
    /**
     * Edit a column in the table: rename and/or change type.
     *
     * @param string $table
     * @param string $column      New column name
     * @param string $oldColumn   Old column name
     * @param string $type        New column type
     * @param string $model
     */
    private function editColumnInTable($parameters)
    {
        try {
            $oldColumn = $parameters['old_column'];
            $newColumn = $parameters['new_column'];
            $table = $parameters['table_name'];
            $oldTableName = $parameters['old_table_name'] ?? null;
            $isSubModuleChanged = $parameters['is_sub_module_changed'] ?? false;

            if($isSubModuleChanged && $oldTableName && Schema::hasColumn($oldTableName, $oldColumn)) {
                // If sub-module changed, drop column from old table if exists
                Schema::table($oldTableName, function (Blueprint $tableBlueprint) use ($oldColumn) {
                    $tableBlueprint->dropColumn($oldColumn);
                });

                //Add column into new table
                if (!Schema::hasColumn($table, $newColumn)) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($newColumn) {
                    $tableBlueprint->longText($newColumn)->nullable();
                });
            }
            }else{
                // Only name is changed
                if (Schema::hasColumn($table, $oldColumn) && $oldColumn !== $newColumn) {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($oldColumn, $newColumn) {
                        $tableBlueprint->renameColumn($oldColumn, $newColumn);
                    });
                }
                // Check if the (possibly new) column exists before changing type
                if (Schema::hasColumn($table, $newColumn)) {
                    Schema::table($table, function (Blueprint $tableBlueprint) use ($newColumn) {
                        $tableBlueprint->longText($newColumn)->nullable()->change();
                    });
                }
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Error editing column in table directly: ' . $e->getMessage());
            return false;
        }
    }

    public function preview(Request $request)
    {
        $id = $request->input('page_id');
        $field_id = $request->input('field_id',null);
        $fieldname = $request->input('fieldname');
        $fieldtype = $request->input('fieldtype');
        $fieldplaceholder = $request->input('fieldplaceholder');
        $mandatory = $request->input('mandatory', true);
        $newoptions = $request->input('dropdown_options', []);
        $is_edit_form = $request->input('is_edit_form')=='true';
        $pageDetail = ModuleBladePageDetail::find($id);
        //custom field which are already added to the page
        $customFields = $pageDetail->is_target_page
                        ? CustomField::where('deleted',false)->whereJsonContains('target_pages', (string)$id)->get()
                        : CustomField::where('deleted',false)->whereJsonContains('display_pages', (string)$id)->get();

        $count = $customFields->count();
        $savedOrder = array_values(json_decode($pageDetail->field_index,true));
        $totalCount = max($savedOrder);
        $total = count($savedOrder);
        $fieldIndex = $savedOrder;
        sort($fieldIndex,SORT_NUMERIC);
        if ($count > 0) {
            // Get last $count elements as custom fields
            $customFieldIndex = array_slice($fieldIndex, -$count);
            // Remove those from main field index
            $fieldIndex = array_slice($fieldIndex, 0, $total - $count);
        } else {
            $customFieldIndex = [];
        }

        $fields = count($customFieldIndex) == 0 ? null : view('admin.custom_field.preview_custom_field',
                    compact(
                        'customFields','pageDetail','customFieldIndex','fieldname','fieldplaceholder',
                        'fieldtype','mandatory','newoptions','is_edit_form','field_id'
                    )
                )->render();
        // If this is an edit form and the field already on the page, we don't need to show the add-on field
        // Otherwise, we render the add-on field view
        $checkFieldExistsonGivenPage = $pageDetail->is_target_page
                        ? CustomField::where('id',$field_id)->where('deleted',false)->whereJsonContains('target_pages', (string)$id)->exists()
                        : CustomField::where('id',$field_id)->where('deleted',false)->whereJsonContains('display_pages', (string)$id)->exists();
        if($is_edit_form && $checkFieldExistsonGivenPage){
            $addonfield = null;
        }else{
            $options = $newoptions;
            $addonfield = view('admin.custom_field.add_on_custom_field',
                    compact('pageDetail','totalCount','fieldname','fieldplaceholder','fieldtype','mandatory','options')
                )->render();
        }

        $preview = view($pageDetail->blade_path)
            ->with([
                'pageDetail' => $pageDetail,
                'fields' => $fields,
                'field_index' => $fieldIndex,
                'addonfield' => $addonfield
            ])->render();

        return response()->json([
            'success' => true,
            'preview' => $preview,
            'savedOrder' => $savedOrder,
        ]);
    }
}
