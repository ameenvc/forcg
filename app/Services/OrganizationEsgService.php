<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/** Organization-wide self-reported readiness snapshot. Never grants vendor qualification. */
class OrganizationEsgService
{
    public function snapshot(array $enabled): array
    {
        $settings = require config_path('esg_score.php');
        $registry = require config_path('my_board.php');
        $indicators = []; $pillars = []; $blockers = [];
        foreach ($settings['pillars'] as $key => $meta) $pillars[$key] = ['name'=>$meta[0], 'weight'=>$meta[1], 'earned'=>0, 'covered'=>0, 'score'=>null, 'coverage'=>0];
        foreach ($settings['indicators'] as $index => $definition) {
            [$label,$pillar,$module,$table,$column,$good,$weight] = $definition;
            $item = ['id'=>$index+1, 'label'=>$label,'pillar'=>$pillar,'module'=>$module,'weight'=>$weight,'score'=>null,'total'=>0,'passed'=>0,'unknown'=>0,'critical'=>0,'state'=>'Evidence required','url'=>null,'satisfactory_states'=>$good];
            if ($module) {
                $item['state'] = in_array($module,$enabled,true) ? 'Source unavailable' : 'Module disabled';
                $route = $registry['modules'][$module][1] ?? null;
                if ($route && \Illuminate\Support\Facades\Route::has($route)) $item['url'] = route($route);
                if (in_array($module,$enabled,true)) {
                    try {
                        if (Schema::hasTable($table) && Schema::hasColumn($table,$column)) {
                            $q=DB::table($table);
                            if (Schema::hasColumn($table,'deleted_at')) $q->whereNull('deleted_at');
                            if (Schema::hasColumn($table,'is_deleted')) $q->where(function($q){$q->whereNull('is_deleted')->orWhere('is_deleted',0);});
                            $groups=$q->select($column)->selectRaw('COUNT(*) AS aggregate_count')->groupBy($column)->get();
                            foreach ($groups as $group) {
                                $state=strtolower(trim((string)$group->$column)); $count=(int)$group->aggregate_count;
                                if (in_array($state,['cancelled','canceled','archived','deleted'],true)) continue;
                                $item['total']+=$count;
                                if ($state==='') $item['unknown']+=$count;
                                if (in_array($state,array_map('strtolower',$good),true)) $item['passed']+=$count;
                                if ($module==='compliance' && $state==='not complied') $item['critical']+=$count;
                            }
                            $item['state']=$item['total'] ? 'Connected' : 'No evidence';
                            if ($item['total']) $item['score']=round(100*$item['passed']/$item['total'],1);
                        }
                    } catch (\Throwable $error) { Log::warning('ESG indicator unavailable',['indicator'=>$index+1,'exception'=>get_class($error)]); }
                }
            }
            if ($item['critical']) $blockers[]=$item['critical'].' legal requirement(s) recorded as Not Complied';
            if ($item['score']!==null) {
                $pillars[$pillar]['earned']+=$weight*$item['score']/100;
                $pillars[$pillar]['covered']+=$weight;
            }
            $indicators[]=$item;
        }
        $overall=0; $coverage=0; $observed=0;
        foreach ($pillars as &$pillar) {
            $pillar['coverage']=$pillar['covered'];
            $pillar['score']=$pillar['covered'] ? round(100*$pillar['earned']/$pillar['covered'],1) : null;
            $overall+=$pillar['weight']*$pillar['earned']/100;
            $coverage+=$pillar['weight']*$pillar['covered']/100;
            if ($pillar['score']!==null) $observed++;
        } unset($pillar);
        $moduleCoverage=[];
        foreach ($registry['modules'] as $key=>$meta) {
            $matches=array_values(array_filter($indicators,function($i)use($key){return $i['module']===$key;}));
            $moduleCoverage[]=['name'=>$meta[0],'enabled'=>in_array($key,$enabled,true),'indicators'=>count($matches),'connected'=>count(array_filter($matches,function($i){return $i['score']!==null;}))];
        }
        return ['version'=>$settings['version'],'at'=>now()->toIso8601String(),'pillars'=>$pillars,'indicators'=>$indicators,
            'score'=>$observed ? round($overall,1) : null,'coverage'=>round($coverage,1),'blockers'=>$blockers,'modules'=>$moduleCoverage,
            'status'=>$blockers ? 'Qualification blocked — compliance review required' : 'Provisional — customer review required'];
    }
}
