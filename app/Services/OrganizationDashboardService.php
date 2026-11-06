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
        $seen=[];$today=now()->toDateString();$soon=now()->addDays(7)->toDateString();$exceptions=0;
        foreach($registry['sources'] as $key=>$source){
            $module=$source['module'];if(!isset($modules[$module]) || !isset($labels[$key]) || isset($seen[$source['table']]))continue;
            $seen[$source['table']]=true;$modules[$module]['expected']++;
            $feed=['name'=>$labels[$key],'connected'=>false,'counts'=>array_fill_keys(array_keys($totals),0),'undated'=>0];
            try{
                $columns=$this->columns($source['table']);
                if(!in_array($source['stage'],$columns,true)){$modules[$module]['feeds'][]=$feed;continue;}
                $fields=array_values(array_unique(array_merge([$source['stage']],array_intersect($source['due'],$columns))));
                $query=DB::table($source['table']);
                if(in_array('deleted_at',$columns,true))$query->whereNull('deleted_at');
                if(in_array('is_deleted',$columns,true))$query->where(function($q){$q->whereNull('is_deleted')->orWhere('is_deleted',0);});
                $feed['connected']=true;
                $closed=array_map('strtolower',array_merge($source['complete'],['closed after due']));
                foreach($query->select($fields)->selectRaw('COUNT(*) AS dashboard_count')->groupBy($fields)->cursor() as $group){
                    $row=(array)$group;$state=strtolower(trim((string)$row[$source['stage']]));$count=(int)$row['dashboard_count'];
                    if(in_array($state,['cancelled','canceled','archived','deleted'],true))continue;
                    $feed['counts']['records']+=$count;
                    if($state===''){$feed['counts']['unknown']+=$count;continue;}
                    if(in_array($state,$closed,true)){$feed['counts']['closed']+=$count;continue;}
                    $feed['counts']['open']+=$count;
                    if(strpos($state,'review')!==false || strpos($state,'approval')!==false)$feed['counts']['decisions']+=$count;
                    $due=null;foreach($source['due'] as $field){$due=$this->date($row[$field]??null);if($due)break;}
                    if(!$due)$feed['undated']+=$count;
                    elseif($due<$today)$feed['counts']['overdue']+=$count;
                    elseif($due<=$soon)$feed['counts']['due_soon']+=$count;
                }
                if($key==='compliance' && in_array('comp_status',$columns,true)){
                    $q=DB::table($source['table'])->whereRaw('LOWER(TRIM(comp_status)) = ?', ['not complied']);
                    if(in_array('deleted_at',$columns,true))$q->whereNull('deleted_at');
                    if(in_array('is_deleted',$columns,true))$q->where(function($q){$q->whereNull('is_deleted')->orWhere('is_deleted',0);});
                    $exceptions=$q->count();
                }
            }catch(\Throwable $e){$feed['connected']=false;Log::warning('Organization dashboard feed unavailable',['source'=>$key,'exception'=>get_class($e)]);}
            $modules[$module]['feeds'][]=$feed;
            if($feed['connected']){$modules[$module]['connected']++;foreach($totals as $metric=>$unused){$modules[$module]['counts'][$metric]+=$feed['counts'][$metric];$totals[$metric]+=$feed['counts'][$metric];}}
        }
        foreach($modules as &$module){$module['state']=$module['expected']===0?'Directory only':($module['connected']===$module['expected']?'Connected':($module['connected']?'Partial coverage':'Data unavailable'));}unset($module);
        $attention=array_values(array_filter($modules,function($m){return $m['counts']['overdue'] || $m['counts']['decisions'];}));
        usort($attention,function($a,$b){return ($b['counts']['overdue']<=>$a['counts']['overdue'])?:($b['counts']['decisions']<=>$a['counts']['decisions']);});
        return ['modules'=>array_values($modules),'totals'=>$totals,'attention'=>array_slice($attention,0,6),'exceptions'=>$exceptions,'at'=>now()->toIso8601String(),'connected'=>count(array_filter($modules,function($m){return $m['connected']>0;}))];
    }
}
