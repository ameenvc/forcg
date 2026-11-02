<?php

namespace App\Http\Controllers\Admin\Calibration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\CalibrationField;
use App\Models\CalibrationFieldHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CalibrationFieldConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:admin', 'mfa']);
    }

    public function index()
    {
        $model = DB::table('modenadisable')->First();
        $calibrationFields = CalibrationField::with('calibrationFieldMetas', 'calibrationFieldHistories.user')
                                ->get()
                                ->map(function ($calibrationField) {
                                    $calibrationFieldArray = $calibrationField->toArray();
                                    $metaArray = $calibrationField->calibrationFieldMetas
                                        ->pluck('meta_value', 'meta_key')
                                        ->toArray();
                                    unset($calibrationFieldArray['calibration_field_metas']);
                                    return (object)array_merge($calibrationFieldArray, $metaArray);
                                });

        return view('admin.calibration.calibration_field_configuration', compact('model', 'calibrationFields'));
    }

    public function store(Request $request)
    {
        $config = $request->config;
        $dataCalibrationFields = json_decode($config, true);

        foreach ($dataCalibrationFields as $key => $dataCalibrationField) {
            $calibrationField = CalibrationField::find($dataCalibrationField['id']);

            if ($calibrationField->enable_field != $dataCalibrationField['enable_field'] ||
                $calibrationField->field_display_label != $dataCalibrationField['field_display_label'] ||
                $calibrationField->field_is_mandatory != $dataCalibrationField['field_is_mandatory']
            ) {
                CalibrationField::where('id', $dataCalibrationField['id'])
                    ->update([
                        'enable_field' => $dataCalibrationField['enable_field'],
                        'field_display_label' => trim($dataCalibrationField['field_display_label']),
                        'field_is_mandatory' => $dataCalibrationField['field_is_mandatory']
                    ]);

                if ($calibrationField->field_display_label != $dataCalibrationField['field_display_label']) {
                    CalibrationFieldHistory::create([
                        'calibration_field_id' => $dataCalibrationField['id'],
                        'previous_display_label' => $calibrationField->field_display_label,
                        'updated_display_label' => trim($dataCalibrationField['field_display_label']),
                        'updated_by' => Auth::guard('admin')->user()->user->id
                    ]);
                }
            }
        }

        $data = CalibrationField::with('calibrationFieldMetas', 'calibrationFieldHistories.user')->get();
        return response()->json([
                'status' => true,
                'message' => 'Calibration form configuration completed succesfully',
                'data' => $data
            ]);
    }
}
