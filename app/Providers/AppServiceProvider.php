<?php

namespace App\Providers;

use App\Area;
use App\Division;
use App\SuperAdmin;
use App\User;
use App\UserGate;
use App\Admin;
use App\ClientDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    public function getEnabled($key, $integerIDs, $userGates)
    {
        $enable_all_user_ids = $userGates->where('key', $key)->pluck('users_id')->toArray();
        $enable_all = array_intersect($enable_all_user_ids, $integerIDs) === $integerIDs ? 1 : 0;
        return $enable_all;
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Central SaaS pages and CLI setup must not query company module tables.
        if (config('tenancy.enabled') && !app(\App\Tenancy\TenantContext::class)->tenant) {
            return;
        }

        // if (app()->environment('remote')) {
        //     URL::forceScheme('https');
        // }
        if (Schema::hasTable('client_details')) {
            $client = ClientDetail::first();
            if ($client && $client->appname_to_display) {
                config(['app.name' => $client->appname_to_display]);
            }
        }
        DB::listen(function ($query) {
            // Check if the SQL query is of type INSERT, UPDATE, or DELETE
            if (preg_match('/^(insert|update|delete|replace)/i', $query->sql)) {
                if (preg_match('/risk_ai_(profiles|knowledge_notes|runs|feedback)/i', $query->sql)) {
                    Log::info('Organization AI data write', ['operation' => strtolower(strtok(ltrim($query->sql), ' '))]);
                    return; // Do not duplicate organization knowledge or answer/feedback contents in debug logs.
                }
                Log::info('SQL Query: ' . $query->sql);
                Log::info('Bindings: ' . json_encode($query->bindings));
                $currentUrl = request()->fullUrl();
                Log::info('Current URL: ' . $currentUrl);
                $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
                $controller = null;
                $method = null;
                $model = null;
        
                foreach ($backtrace as $trace) {
                    // Detect controller and method
                    if (isset($trace['class']) && strpos($trace['class'], 'App\\Http\\Controllers') !== false) {
                        $controller = $trace['class'];
                        $method = $trace['function'];
                    }
        
                    // Detect model
                    if (isset($trace['class']) && strpos($trace['class'], 'App\\Models') !== false) {
                        $model = $trace['class'];
                    }
        
                    if ($controller && $model) {
                        break;
                    }
                }
        
                if ($controller) {
                    Log::info("Executed in Controller: $controller::$method");
                } 
                if ($model) {
                    Log::info("Executed in Model: $model");
                }
            }
        });
        $excludeView = [
            'user.risks._risk_detail',
            'user.risks.mitigation._form',
            'user.risks.mitigation._status',
            'user.risks.mitigation._mitigation_detail',
            'user.risks.mitigation._mitigation_attachment',
            'user.risks._analysis_group_item',
            'user.risks.organization-ai-answer',
            'shared.theme.assets',
            'shared.typography.assets',
            'shared.forms.theme-control',
            'shared.forms.assets',
            'shared.forms.risk-assets',
            'shared.forms.risk-page-header',
            'exporting.forcorrective'
        ];

        if (env('FORCE_HTTPS', false)) { // Default value should be false for local server
            URL::forceScheme('https');
        }

        view()->composer('*', function ($view) use ($excludeView) {
            if (Auth::check() && !in_array($view->getName(), $excludeView)) {
                $division_count = Division::count();
                $area_count = Area::count();
                View::share('division_count', $division_count);
                View::share('area_count', $area_count);

                //$auth_user = User::where('email', Auth::user()->email)->first();
                $user_table_exists = User::where('email', Auth::user()->email)->first();
                if($user_table_exists){
                    $auth_user = User::where('email', Auth::user()->email)->first();
                }else{
                    $super_admin_exists = SuperAdmin::where('email',Auth::user()->email)->first();
                    if($super_admin_exists){
                        $auth_user = SuperAdmin::where('email', Auth::user()->email)->first();
                    }
                }

                $ids = session('enable_page_ids');
                $integerIDs = $ids && count($ids) ? array_map('intval', $ids) : [];
                $userGate = UserGate::where('users_id', $auth_user->id)->get();
                $userGates = UserGate::whereIn('users_id', $integerIDs)->get();

                $enable_all = $this->getEnabled('enable-all', $integerIDs, $userGates);
                $enable_auth = $userGate->where('key', 'enable-all')->first();

                $complaint_all_pages = $this->getEnabled('complaint-all-pages', $integerIDs, $userGates);
                $complaint_auth = $userGate->where('key', 'complaint-all-pages')->first();

                $compliance_all_pages = $this->getEnabled('compliance-all-pages', $integerIDs, $userGates);
                $compliance_auth = $userGate->where('key', 'compliance-all-pages')->first();

                $compliance_analysis_page = $this->getEnabled('compliance-analysis-page', $integerIDs, $userGates);
                $compliance_analysis_auth = $userGate->where('key', 'compliance-analysis-page')->first();

                $risk_all_pages = $this->getEnabled('risk-all-pages', $integerIDs, $userGates);
                $risk_auth = $userGate->where('key', 'risk-all-pages')->first();

                $risk_analysis_page = $this->getEnabled('risk-analysis-page', $integerIDs, $userGates);
                $risk_analysis_auth = $userGate->where('key', 'risk-analysis-page')->first();

                $objective_all_pages = $this->getEnabled('objective-all-pages', $integerIDs, $userGates);
                $objective_auth = $userGate->where('key', 'objective-all-pages')->first();

                $envi_all_pages = $this->getEnabled('envi-all-pages', $integerIDs, $userGates);
                $envi_auth = $userGate->where('key', 'envi-all-pages')->first();

                $envi_analysis_page = $this->getEnabled('envi-analysis-page', $integerIDs, $userGates);
                $envi_analysis_auth = $userGate->where('key', 'envi-analysis-page')->first();

                $opportunity_all_pages = $this->getEnabled('opportunity-all-pages', $integerIDs, $userGates);
                $opportunity_auth = $userGate->where('key', 'opportunity-all-pages')->first();

                $opportunity_analysis_page = $this->getEnabled('opportunity-analysis-page', $integerIDs, $userGates);
                $opportunity_analysis_auth = $userGate->where('key', 'opportunity-analysis-page')->first();

                $hse_all_pages = $this->getEnabled('hse-all-pages', $integerIDs, $userGates);
                $hse_auth = $userGate->where('key', 'hse-all-pages')->first();

                $hse_analysis_page = $this->getEnabled('hse-analysis-page', $integerIDs, $userGates);
                $hse_analysis_auth = $userGate->where('key', 'hse-analysis-page')->first();

                $corrective_all_pages = $this->getEnabled('corrective-all-pages', $integerIDs, $userGates);
                $corrective_auth = $userGate->where('key', 'corrective-all-pages')->first();

                $corrective_analysis_page = $this->getEnabled('corrective-analysis-page', $integerIDs, $userGates);
                $corrective_analysis_auth = $userGate->where('key', 'corrective-analysis-page')->first();

                $hsr_all_pages = $this->getEnabled('hsr-all-pages', $integerIDs, $userGates);
                $hsr_auth = $userGate->where('key', 'hsr-all-pages')->first();

                $document_all_pages = $this->getEnabled('document-all-pages', $integerIDs, $userGates);
                $document_auth = $userGate->where('key', 'document-all-pages')->first();

                $document_analysis_page = $this->getEnabled('document-analysis-page', $integerIDs, $userGates);
                $document_analysis_auth = $userGate->where('key', 'document-analysis-page')->first();

                $audit_all_pages = $this->getEnabled('audit-all-pages', $integerIDs, $userGates);
                $audit_auth = $userGate->where('key', 'audit-all-pages')->first();

                $audit_analysis_page = $this->getEnabled('audit-analysis-page', $integerIDs, $userGates);
                $audit_analysis_auth = $userGate->where('key', 'audit-analysis-page')->first();

                $management_all_pages = $this->getEnabled('management-all-pages', $integerIDs, $userGates);
                $management_auth = $userGate->where('key', 'management-all-pages')->first();

                $management_analysis_page = $this->getEnabled('management-analysis-page', $integerIDs, $userGates);
                $management_analysis_auth = $userGate->where('key', 'management-analysis-page')->first();

                $incident_all_pages = $this->getEnabled('incident-all-pages', $integerIDs, $userGates);
                $incident_auth = $userGate->where('key', 'management-analysis-page')->first();

                $hr_all_pages = $this->getEnabled('hr-all-pages', $integerIDs, $userGates);
                $hr_auth = $userGate->where('key', 'hr-all-pages')->first();

                $vendor_all_pages = $this->getEnabled('vendor-all-pages', $integerIDs, $userGates);
                $vendor_auth = $userGate->where('key', 'vendor-all-pages')->first();

                $vendor_analysis_page =  $this->getEnabled('vendor-analysis-page', $integerIDs, $userGates);
                $vendor_analysis_auth = $userGate->where('key', 'vendor-analysis-page')->first();

                $waste_all_pages = $this->getEnabled('waste-all-pages', $integerIDs, $userGates);
                $waste_auth = $userGate->where('key', 'waste-all-pages')->first();

                $waste_analysis_page = $this->getEnabled('waste-analysis-page', $integerIDs, $userGates);
                $waste_analysis_auth = $userGate->where('key', 'waste-analysis-page')->first();

                $calibration_all_pages = $this->getEnabled('calibration-all-pages', $integerIDs, $userGates);
                $calibration_auth = $userGate->where('key', 'calibration-all-pages')->first();

                $calibration_analysis_page = $this->getEnabled('calibration-analysis-page', $integerIDs, $userGates);
                $calibration_analysis_auth = $userGate->where('key', 'calibration-analysis-page')->first();

                $nearmiss_all_pages = $this->getEnabled('nearmiss-all-pages', $integerIDs, $userGates);
                $nearmiss_auth = $userGate->where('key', 'nearmiss-all-pages')->first();

                $nearmiss_analysis_page = $this->getEnabled('nearmiss-analysis-page', $integerIDs, $userGates);
                $nearmiss_analysis_auth = $userGate->where('key', 'nearmiss-analysis-page')->first();

                $occhealth_all_pages = $this->getEnabled('occhealth-all-pages', $integerIDs, $userGates);
                $occhealth_auth = $userGate->where('key', 'occhealth-all-pages')->first();

                $occhealth_analysis_page = $this->getEnabled('occhealth-analysis-page', $integerIDs, $userGates);
                $occhealth_analysis_auth = $userGate->where('key', 'occhealth-analysis-page')->first();

                $maintenance_all_pages = $this->getEnabled('maintenance-all-pages', $integerIDs, $userGates);
                $maintenance_auth = $userGate->where('key', 'maintenance-all-pages')->first();

                $elearning_all_pages = $this->getEnabled('elearning-all-pages', $integerIDs, $userGates);
                $elearning_auth = $userGate->where('key', 'elearning-all-pages')->first();

                $context_all_pages = $this->getEnabled('context-all-pages', $integerIDs, $userGates);
                $context_auth = $userGate->where('key', 'context-all-pages')->first();

                $customer_all_pages = $this->getEnabled('customer-all-pages', $integerIDs, $userGates);
                $customer_auth = $userGate->where('key', 'customer-all-pages')->first();

                $customer_analysis_page = $this->getEnabled('customer-analysis-page', $integerIDs, $userGates);
                $customer_analysis_auth = $userGate->where('key', 'customer-analysis-page')->first();

                $emergency_all_pages = $this->getEnabled('emergency-all-pages', $integerIDs, $userGates);
                $emergency_auth = $userGate->where('key', 'emergency-all-pages')->first();

                $enable_dashboard = $this->getEnabled('enable-dashboard', $integerIDs, $userGates);
                $enable_dashboard_auth = $userGate->where('key', 'emergency-all-pages')->first();

                $enable_all_condition = ($enable_dashboard && $complaint_all_pages && $compliance_all_pages && $compliance_analysis_page && $context_all_pages && $customer_all_pages && $customer_analysis_page && $emergency_all_pages && $risk_all_pages && $risk_analysis_page && $objective_all_pages && $envi_all_pages && $envi_analysis_page && $opportunity_all_pages && $opportunity_analysis_page && $hse_all_pages && $hse_analysis_page && $corrective_all_pages && $corrective_analysis_page && $hsr_all_pages && $document_all_pages && $document_analysis_page && $audit_all_pages && $audit_analysis_page && $management_all_pages && $management_analysis_page && $incident_all_pages && $hr_all_pages && $vendor_all_pages && $vendor_analysis_page && $waste_all_pages && $waste_analysis_page && $calibration_all_pages && $calibration_analysis_page && $nearmiss_all_pages && $nearmiss_analysis_page && $occhealth_all_pages && $occhealth_analysis_page && $maintenance_all_pages && $elearning_all_pages);

                $view->with('complaint_all_pages', $complaint_all_pages);
                $view->with('complaint_auth', $complaint_auth);
                $view->with('compliance_all_pages', $compliance_all_pages);
                $view->with('compliance_auth', $compliance_auth);
                $view->with('compliance_analysis_page', $compliance_analysis_page);
                $view->with('compliance_analysis_auth', $compliance_analysis_auth);
                $view->with('risk_all_pages', $risk_all_pages);
                $view->with('risk_auth', $risk_auth);
                $view->with('risk_analysis_page', $risk_analysis_page);
                $view->with('risk_analysis_auth', $risk_analysis_auth);
                $view->with('objective_all_pages', $objective_all_pages);
                $view->with('objective_auth', $objective_auth);
                $view->with('envi_all_pages', $envi_all_pages);
                $view->with('envi_auth', $envi_auth);
                $view->with('envi_analysis_page', $envi_analysis_page);
                $view->with('envi_analysis_auth', $envi_analysis_auth);
                $view->with('opportunity_all_pages', $opportunity_all_pages);
                $view->with('opportunity_auth', $opportunity_auth);
                $view->with('opportunity_analysis_page', $opportunity_analysis_page);
                $view->with('opportunity_analysis_auth', $opportunity_analysis_auth);
                $view->with('hse_all_pages', $hse_all_pages);
                $view->with('hse_auth', $hse_auth);
                $view->with('hse_analysis_page', $hse_analysis_page);
                $view->with('hse_analysis_auth', $hse_analysis_auth);
                $view->with('corrective_all_pages', $corrective_all_pages);
                $view->with('corrective_auth', $corrective_auth);
                $view->with('corrective_analysis_page', $corrective_analysis_page);
                $view->with('corrective_analysis_auth', $corrective_analysis_auth);
                $view->with('hsr_all_pages', $hsr_all_pages);
                $view->with('hsr_auth', $hsr_auth);
                $view->with('document_all_pages', $document_all_pages);
                $view->with('document_auth', $document_auth);
                $view->with('document_analysis_page', $document_analysis_page);
                $view->with('document_analysis_auth', $document_analysis_auth);
                $view->with('audit_all_pages', $audit_all_pages);
                $view->with('audit_auth', $audit_auth);
                $view->with('audit_analysis_page', $audit_analysis_page);
                $view->with('audit_analysis_auth', $audit_analysis_auth);
                $view->with('management_all_pages', $management_all_pages);
                $view->with('management_auth', $management_auth);
                $view->with('management_analysis_page', $management_analysis_page);
                $view->with('management_analysis_auth', $management_analysis_auth);
                $view->with('incident_all_pages', $incident_all_pages);
                $view->with('incident_auth', $incident_auth);
                $view->with('hr_all_pages', $hr_all_pages);
                $view->with('hr_auth', $hr_auth);
                $view->with('vendor_all_pages', $vendor_all_pages);
                $view->with('vendor_auth', $vendor_auth);
                $view->with('vendor_analysis_page', $vendor_analysis_page);
                $view->with('vendor_analysis_auth', $vendor_analysis_auth);
                $view->with('waste_all_pages', $waste_all_pages);
                $view->with('waste_auth', $waste_auth);
                $view->with('waste_analysis_page', $waste_analysis_page);
                $view->with('waste_analysis_auth', $waste_analysis_auth);
                $view->with('calibration_all_pages', $calibration_all_pages);
                $view->with('calibration_auth', $calibration_auth);
                $view->with('calibration_analysis_page', $calibration_analysis_page);
                $view->with('calibration_analysis_auth', $calibration_analysis_auth);
                $view->with('nearmiss_all_pages', $nearmiss_all_pages);
                $view->with('nearmiss_auth', $nearmiss_auth);
                $view->with('nearmiss_analysis_page', $nearmiss_analysis_page);
                $view->with('nearmiss_analysis_auth', $nearmiss_analysis_auth);
                $view->with('occhealth_all_pages', $occhealth_all_pages);
                $view->with('occhealth_auth', $occhealth_auth);
                $view->with('occhealth_analysis_page', $occhealth_analysis_page);
                $view->with('occhealth_analysis_auth', $occhealth_analysis_auth);
                $view->with('maintenance_all_pages', $maintenance_all_pages);
                $view->with('maintenance_auth', $maintenance_auth);
                $view->with('elearning_all_pages', $elearning_all_pages);
                $view->with('elearning_auth', $elearning_auth);
                $view->with('context_all_pages', $context_all_pages);
                $view->with('context_auth', $context_auth);
                $view->with('customer_all_pages', $customer_all_pages);
                $view->with('customer_auth', $customer_auth);
                $view->with('customer_analysis_page', $customer_analysis_page);
                $view->with('customer_analysis_auth', $customer_analysis_auth);
                $view->with('emergency_all_pages', $emergency_all_pages);
                $view->with('emergency_auth', $emergency_auth);
                $view->with('enable_all', $enable_all);
                $view->with('enable_all_condition', $enable_all_condition);
                $view->with('enable_auth', $enable_auth);
                $view->with('enable_dashboard', $enable_dashboard);
                $view->with('enable_dashboard_auth', $enable_dashboard_auth);


            }

        });

    }
}
