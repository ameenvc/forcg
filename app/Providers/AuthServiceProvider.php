<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $keys = [
                 'edit-nearmiss', 'delete-nearmiss',
                 'edit-maintenance', 'delete-maintenance',
                 'edit-doctor', 'delete-doctor',
                 'add-calibration', 'edit-calibration', 'delete-calibration',
                 'edit-waste', 'delete-waste','edit-waste-transferrequest', 'delete-waste-transferrequest',
                 'review-vendor', 'approve-vendor','edit-vendor', 'delete-vendor',
                 'edit-hr','delete-hr',
                 'edit-incident', 'delete-incident','change-incident-reviewer-approver',
                 'edit-audit', 'delete-audit',
                 'audit-responsibility-change','audit-checklist-edit','audit-checklist-delete','audit-checklist-records-edit','audit-checklist-records-approve',
                 'management-review-meeting-controls','management-review-meeting-delete',
                 'edit-doc', 'delete-doc', 'edit-health','delete-health',
                 'edit-corrective','delete-corrective','change-objective-reviewer-approver',
                 'edit-hse', 'delete-hse',
                 'edit-opportunity','delete-opportunity',
                 'edit-enviaspect','delete-enviaspect', 'enviaspect-secondary-review',
                 'edit-objective','delete-objective','objective-change-responsible-person',
                 'edit-risk','delete-risk',
                 'edit-compliance','delete-compliance',
                 //'compliance-change-legal-responsibility',
                 'edit-complaint','delete-complaint',
                 'edit-all','delete-all',
                 'corrective-responsibility-change','hse-responsibility-change','nearmiss-responsibility-change',
                 'opportunity-responsibility-change','risk-change-riskowner', 'hse-observation-reviewer',
                 'ohsrisk-secondary-review','complaint-responsibility-change',
                 'elearning-add-content',
                 'complaint-all-pages',
                 'compliance-all-pages',
                 'compliance-analysis-page',
                 'risk-all-pages',
                 'risk-analysis-page',
                 'objective-all-pages',
                 'envi-all-pages',
                 'envi-analysis-page',
                 'opportunity-all-pages',
                 'opportunity-analysis-page',
                 'hse-all-pages',
                 'hse-analysis-page',
                 'corrective-all-pages',
                 'corrective-analysis-page',
                 'hsr-all-pages',
                 'document-all-pages',
                 'document-analysis-page',
                 'audit-all-pages',
                 'audit-analysis-page',
                 'management-all-pages',
                 'management-analysis-page',
                 'incident-all-pages',
                 'hr-all-pages',
                 'vendor-all-pages',
                 'vendor-analysis-page',
                 'waste-all-pages',
                 'waste-analysis-page',
                 'calibration-all-pages',
                 'calibration-analysis-page',
                 'nearmiss-all-pages',
                 'nearmiss-analysis-page',
                 'occhealth-all-pages',
                 'occhealth-analysis-page',
                 'maintenance-all-pages',
                 'elearning-all-pages',
                 'context-all-pages',
                 'customer-all-pages',
                 'customer-analysis-page',
                 'emergency-all-pages',
                 'enable-all',
                 'enable-dashboard',
                 'objective-reviewer',
                 'objective-approver',
                 'escalate-risk-button-access'
                ];

        //if (Schema::hasTable('user_gates')) {
            foreach($keys as $key) {
                Gate::define($key, function ($user) use($key) {
                    $user = User::where('email', $user->email)->first();
                    $gates = $user->gates()->where('key', $key)->where('users_id', $user->id)->get();
                    return count($gates) > 0;
                });
            }
        //}
        Gate::define('manage-risk-ai-knowledge', function ($user) {
            if ($user instanceof \App\Admin) return true;
            return $user instanceof User && \Illuminate\Support\Facades\Schema::hasTable('user_gates')
                && $user->gates()->where('key', 'manage-risk-ai-knowledge')->exists();
        });
    }
}
