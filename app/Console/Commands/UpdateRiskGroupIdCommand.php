<?php

namespace App\Console\Commands;

use App\Risk;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateRiskGroupIdCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:risk-group-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command updates grp_id that be the same in the risk table to unique';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Start updating risk group id');
        $riskGroups = DB::table('risks')
            ->select('grp_id')
            ->groupBy('grp_id')
            ->havingRaw('count(*) > 1')
            ->get();
        $riskLogs = [];
        $mitigationLogs = [];
        $riskLogs[] = [
            'Rid',
            'grp_id',
            'new_grp_id',
        ];
        $mitigationLogs[] = [
            'Id',
            'origin_action_id',
            'new_action_id',
        ];
        foreach ($riskGroups as $riskGroup) {
            $risks = DB::table('risks')->where('grp_id', $riskGroup->grp_id)->orderBy('created_at', 'DESC')->get();
            foreach ($risks as $risk) {
                $this->info('Risk ID: ' . $risk->Rid);
                $lastRiskItemOfGroup = Risk::where('riskgroup', $risk->riskgroup)->orderBy('count', 'desc')->first();
                if ($lastRiskItemOfGroup) {
                    $count = $lastRiskItemOfGroup->count + 1;
                } else {
                    $count = "1";
                }
                $code = strtoupper(substr($risk->riskgroup, 0, 3));
                $newGroupId = $code . $count;
                DB::table('risks')->where('Rid', $risk->Rid)->update([
                    'count' => $count,
                    'grp_id' => $newGroupId,
                ]);
                $this->info('Update grp_id from: ' . $risk->grp_id . ' to: ' . $newGroupId);
                $riskLogs[] = [
                    $risk->Rid,
                    $risk->grp_id,
                    $newGroupId,
                ];
                $mitigations = DB::table('mitigations')->where('risk_id', $risk->Rid)->get();

                foreach ($mitigations as $mitigation) {
                    $this->info('Mitigation ID: ' . $mitigation->id);
                    $originActionId = $mitigation->action_id;
                    $actionIdItems = explode('/', $originActionId);
                    $newActionId = $newGroupId . '/' . $actionIdItems[1] . '/' . $actionIdItems[2];
                    DB::table('mitigations')->where('id', $mitigation->id)->update([
                        'action_id' => $newActionId,
                    ]);
                    $this->info('Update action_id from: ' . $originActionId . ' to: ' . $newActionId);
                    $mitigationLogs[] = [
                        $mitigation->id,
                        $originActionId,
                        $newActionId,
                    ];
                }
            }
        }
        Log::info('UpdateRiskGroupIdCommand');
        Log::info(json_encode($riskLogs));
        Log::info(json_encode($mitigationLogs));
        $this->info('End updating risk group id');
    }
}
