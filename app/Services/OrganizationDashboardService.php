<?php
namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/** Aggregate organization snapshot. Never loads medical details, task contents or employee activity. */
class OrganizationDashboardService
{
    private $schema;
    private function columns($table)
    {
        if ($this->schema===null) {
            $this->schema=[];
            if(DB::connection()->getDriverName()==='mysql'){
                $prefix=DB::connection()->getTablePrefix();
                foreach(DB::select('SELECT TABLE_NAME AS dashboard_table, COLUMN_NAME AS dashboard_column FROM information_schema.columns WHERE table_schema = ?', [DB::connection()->getDatabaseName()]) as $c){
                    $name=$c->dashboard_table;if($prefix && strpos($name,$prefix)===0)$name=substr($name,strlen($prefix));$this->schema[$name][]=$c->dashboard_column;
                }
                $this->schema['__complete']=true;
            }
        }
        if(!array_key_exists($table,$this->schema))$this->schema[$table]=isset($this->schema['__complete'])?[]:(Schema::hasTable($table)?Schema::getColumnListing($table):[]);
        return $this->schema[$table];
    }
    private function date($value)
    {
        if(!$value)return null;
        $value=trim((string)$value);
        foreach(['Y-m-d','Y-m-d H:i:s','d/m/Y','d-m-Y'] as $format){try{$date=Carbon::createFromFormat('!'.$format,$value,config('app.timezone'));if($date && $date->format($format)===$value)return $date->toDateString();}catch(\Exception $e){}}
        return null;
    }
    public function snapshot(array $enabled):array
    {
        $registry=require config_path('my_board.php');$modules=[];$totals=['records'=>0,'open'=>0,'closed'=>0,'overdue'=>0,'due_soon'=>0,'decisions'=>0,'unknown'=>0];
        $labels=['risks'=>'Risk register','mitigations'=>'Mitigation actions','criteria'=>'Assessment criteria','audit_findings'=>'Audit findings','audit_schedules'=>'Audit schedules','corrective'=>'Corrective actions','hse_observations'=>'HSE observations','near_misses'=>'Near misses','complaints'=>'Customer complaints','opportunities'=>'Opportunities','objectives'=>'Objectives','compliance'=>'Compliance requirements','document_drafts'=>'Document drafts','training'=>'Training sessions','learning'=>'Learning assignments','maintenance'=>'Maintenance records','calibration'=>'Calibration instruments','emergency_drills'=>'Emergency drills','incidents'=>'Incident investigations','waste'=>'Waste records','vendor_evaluations'=>'Supplier evaluations','meetings'=>'Management meetings','environment_reviews'=>'Environmental assessments','environmental_aspects'=>'Environmental aspects','safety_risks'=>'Safety risks','context_changes'=>'Context changes'];
        foreach($registry['modules'] as $key=>$meta){if(!in_array($key,$enabled,true))continue;
            $modules[$key]=['key'=>$key,'name'=>$meta[0],'area'=>$meta[3],'icon'=>$meta[2],'url'=>Route::has($meta[1])?route($meta[1]):null,'feeds'=>[],'connected'=>0,'expected'=>0,'counts'=>array_fill_keys(array_keys($totals),0),'state'=>'Directory only'];
        }
        $seen=[];$today=now()->toDateString();$soon=now()->addDays(7)->toDateString();$exceptions=0;$risk=['available'=>false,'High'=>0,'Moderate'=>0,'Low'=>0,'Unassessed'=>0];$compliance=['available'=>false,'Complied'=>0,'Partially Complied'=>0,'Not Complied'=>0,'Other'=>0];
        foreach($registry['sources'] as $key=>$source){
            $module=$source['module'];if(!isset($modules[$module]) || !isset($labels[$key]) || isset($seen[$source['table']]))continue;
            $seen[$source['table']]=true;$modules[$module]['expected']++;
            $feed=['name'=>$labels[$key],'connected'=>false,'counts'=>array_fill_keys(array_keys($totals),0),'undated'=>0];
            try{
                $columns=$this->columns($source['table']);
                if(!in_array($source['stage'],$columns,true)){$modules[$module]['feeds'][]=$feed;continue;}
                $fields=array_values(array_unique(array_merge([$source['stage']],array_intersect($source['due'],$columns))));
                if($key==='risks' && in_array('rank',$columns,true)){$fields[]='rank';$risk['available']=true;}
                if($key==='compliance' && in_array('comp_status',$columns,true)){$fields[]='comp_status';$fields=array_values(array_unique($fields));$compliance['available']=true;}
                $decisionStages=['under review','review pending','review requested','awaiting review','awaiting approval','approval pending','pending approval','entry under review','under secondary review'];
                foreach($source['roles'] as $role)if(in_array($role['kind'],['review','approval'],true)){
                    if(is_array($role['pending']))$decisionStages=array_merge($decisionStages,array_map('strtolower',$role['pending']));
                    if(isset($role['when_progress'],$source['progress']) && in_array($source['progress'],$columns,true))$fields[]=$source['progress'];
                }
                $fields=array_values(array_unique($fields));
                $query=DB::table($source['table']);
                if(in_array('deleted_at',$columns,true))$query->whereNull('deleted_at');
                if(in_array('is_deleted',$columns,true))$query->where(function($q){$q->whereNull('is_deleted')->orWhere('is_deleted',0);});
                $feed['connected']=true;
                $closed=array_map('strtolower',array_merge($source['complete'],['closed after due']));
                foreach($query->select($fields)->selectRaw('COUNT(*) AS dashboard_count')->groupBy($fields)->cursor() as $group){
                    $row=(array)$group;$state=strtolower(trim((string)$row[$source['stage']]));$count=(int)$row['dashboard_count'];
                    if(in_array($state,['cancelled','canceled','archived','deleted'],true))continue;
                    $feed['counts']['records']+=$count;
                    if($key==='compliance' && $compliance['available']){
                        $value=strtolower(trim((string)($row['comp_status']??'')));$bucket='Other';
                        foreach(['Complied','Partially Complied','Not Complied'] as $name)if(strtolower($name)===$value)$bucket=$name;
                        $compliance[$bucket]+=$count;
                    }
                    if($state===''){$feed['counts']['unknown']+=$count;continue;}
                    if(in_array($state,$closed,true)){$feed['counts']['closed']+=$count;continue;}
                    $feed['counts']['open']+=$count;
                    if($key==='risks' && $risk['available']){
                        $value=ucfirst(strtolower(trim((string)($row['rank']??''))));$risk[in_array($value,['High','Moderate','Low'],true)?$value:'Unassessed']+=$count;
                    }
                    $decision=in_array($state,$decisionStages,true);
                    foreach($source['roles'] as $role)if(in_array($role['kind'],['review','approval'],true) && isset($role['when_progress'],$source['progress']) && isset($row[$source['progress']]) && (float)$row[$source['progress']]===(float)$role['when_progress'])$decision=true;
                    if($decision)$feed['counts']['decisions']+=$count;
                    $due=null;foreach($source['due'] as $field){$due=$this->date($row[$field]??null);if($due)break;}
                    if(!$due)$feed['undated']+=$count;
                    elseif($due<$today)$feed['counts']['overdue']+=$count;
                    elseif($due<=$soon)$feed['counts']['due_soon']+=$count;
                }
                if($key==='compliance' && $compliance['available'])$exceptions=$compliance['Not Complied'];
            }catch(\Throwable $e){$feed['connected']=false;if($key==='risks')$risk['available']=false;if($key==='compliance'){$compliance['available']=false;$exceptions=0;}Log::warning('Organization dashboard feed unavailable',['source'=>$key,'exception'=>get_class($e)]);}
            $modules[$module]['feeds'][]=$feed;
            if($feed['connected']){$modules[$module]['connected']++;foreach($totals as $metric=>$unused){$modules[$module]['counts'][$metric]+=$feed['counts'][$metric];$totals[$metric]+=$feed['counts'][$metric];}}
        }
        foreach($modules as &$module){$module['state']=$module['expected']===0?'Directory only':($module['connected']===$module['expected']?'Connected':($module['connected']?'Partial coverage':'Data unavailable'));}unset($module);
        $attention=array_values(array_filter($modules,function($m){return $m['counts']['overdue'] || $m['counts']['decisions'];}));
        usort($attention,function($a,$b){return ($b['counts']['overdue']<=>$a['counts']['overdue'])?:($b['counts']['decisions']<=>$a['counts']['decisions']);});
        $areas=[];$closure=[];$upcoming=[];
        foreach($modules as $module){
            $area=$module['area'];if(!isset($areas[$area]))$areas[$area]=['name'=>$area,'modules'=>0,'connected'=>0,'overdue'=>0,'decisions'=>0];
            $areas[$area]['modules']++;if($module['connected'])$areas[$area]['connected']++;
            $areas[$area]['overdue']+=$module['counts']['overdue'];$areas[$area]['decisions']+=$module['counts']['decisions'];
            foreach($module['feeds'] as $feed)if($feed['connected'] && $feed['counts']['due_soon'])$upcoming[]=['module'=>$module['name'],'name'=>$feed['name'],'count'=>$feed['counts']['due_soon'],'url'=>$module['url']];
            foreach($module['feeds'] as $feed)if($feed['connected'] && in_array($feed['name'],['Mitigation actions','Corrective actions','Audit findings','HSE observations','Near misses'],true)){
                $closure[]=['name'=>$feed['name'],'closed'=>$feed['counts']['closed'],'total'=>$feed['counts']['records'],'url'=>$module['url']];
            }
        }
        usort($upcoming,function($a,$b){return $b['count']<=>$a['count'];});
        $evidence=['available'=>false,'accepted'=>0,'pending'=>0,'assured'=>0];
        if(count($modules) && !array_diff(['status','assurance_path'],$this->columns('esg_evidence'))){
            $evidence['available']=true;
            foreach(DB::table('esg_evidence')->select('status')->selectRaw('COUNT(*) AS record_count, SUM(CASE WHEN assurance_path IS NOT NULL THEN 1 ELSE 0 END) AS assured_count')->groupBy('status')->get() as $group){
                if(in_array($group->status,['accepted','pending'],true))$evidence[$group->status]=(int)$group->record_count;
                if($group->status==='accepted')$evidence['assured']=(int)$group->assured_count;
            }
        }
        $departmentPerformance=(new DepartmentPerformanceService)->snapshot($enabled,function($table){return $this->columns($table);});
        return ['department_performance'=>$departmentPerformance,'modules'=>array_values($modules),'totals'=>$totals,'attention'=>array_slice($attention,0,6),'exceptions'=>$exceptions,'risk'=>$risk,'compliance'=>$compliance,'closure'=>$closure,'upcoming'=>$upcoming,'areas'=>array_values($areas),'evidence'=>$evidence,'at'=>now()->toIso8601String(),'connected'=>count(array_filter($modules,function($m){return $m['connected']>0;}))];
    }
}
