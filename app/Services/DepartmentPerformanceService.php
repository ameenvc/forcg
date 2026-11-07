<?php
namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

/** Provisional operational comparison. No incident-volume penalty or employee ranking. */
class DepartmentPerformanceService
{
    private $schema=[];
    private $resolver;
    private function columns($table){
        if(!isset($this->schema[$table]))$this->schema[$table]=$this->resolver ? call_user_func($this->resolver,$table) : (Schema::hasTable($table)?Schema::getColumnListing($table):[]);
        return $this->schema[$table];
    }
    private function date($value){
        if(!$value)return null;$value=trim((string)$value);
        foreach(['Y-m-d','Y-m-d H:i:s','d/m/Y','d-m-Y'] as $format){try{$d=Carbon::createFromFormat('!'.$format,$value,config('app.timezone'));if($d&&$d->format($format)===$value)return $d->startOfDay();}catch(\Exception $e){}}
        return null;
    }
    private function missingStatus($state){return in_array($state,['','null','n/a','na','none','-','--'],true);}
    private function query($table,$fields){
        $columns=$this->columns($table);$q=DB::table($table);
        if(in_array('deleted_at',$columns,true))$q->whereNull('deleted_at');
        if(in_array('is_deleted',$columns,true))$q->where(function($q){$q->whereNull('is_deleted')->orWhere('is_deleted',0);});
        return $q->select($fields)->selectRaw('COUNT(*) AS performance_count')->groupBy($fields)->cursor();
    }
    private function emptyMetric(){return ['total'=>0,'passed'=>0,'unknown'=>0,'sources'=>[]];}
    public function snapshot(array $enabled,?callable $columns=null):array
    {
        $this->resolver=$columns;$this->schema=[];$config=require config_path('department_performance.php');
        $today=now()->startOfDay();$start=$today->copy()->subDays($config['days']-1);
        $result=['available'=>false,'version'=>$config['version'],'start'=>$start->toDateString(),'end'=>$today->toDateString(),'minimum_records'=>$config['minimum_records'],'minimum_coverage'=>$config['minimum_coverage'],'departments'=>[],'cohorts'=>[],'sources'=>[],'unassigned'=>0];
        if(!$enabled || array_diff(['id','departmentname'],$this->columns('departments')))return $result;
        $q=DB::table('departments')->select('id','departmentname');$dc=$this->columns('departments');
        if(in_array('is_active',$dc,true))$q->where('is_active',1);
        if(in_array('deleted_at',$dc,true))$q->whereNull('deleted_at');
        $departments=[];
        foreach($q->orderBy('departmentname')->get() as $d)$departments[(string)$d->id]=['id'=>(int)$d->id,'name'=>$d->departmentname,'metrics'=>array_fill_keys(array_keys($config['metrics']),$this->emptyMetric()),'records'=>0,'overdue'=>0,'missing_dates'=>0,'missing_severity'=>0,'weight'=>0,'penalty'=>0,'scope'=>[]];
        $result['available']=true;
        foreach($config['actions'] as $mapping){
            [$module,$table,$department,$stage,$dueField,$completionFields,$severityField,$closed]=$mapping;
            if(!in_array($module,$enabled,true))continue;
            $source=['name'=>$table,'module'=>$module,'available'=>false];$cols=$this->columns($table);
            if(array_diff([$department,$stage,$dueField],$cols)){$result['sources'][]=$source;continue;}
            $fields=array_values(array_unique(array_merge([$department,$stage,$dueField],array_intersect(array_merge($completionFields,[$severityField]),$cols))));
            // Keep a source atomic: a failed aggregate cannot leave half its points in the comparison.
            $working=$departments;$unassigned=0;
            try{
                foreach($this->query($table,$fields) as $record){
                    $row=(array)$record;$state=strtolower(trim((string)$row[$stage]));$count=(int)$row['performance_count'];
                    if(in_array($state,['cancelled','canceled','archived','deleted'],true))continue;
                    $key=trim((string)$row[$department]);
                    if(!preg_match('/^[1-9][0-9]*$/D',$key)||!isset($working[$key])){$unassigned+=$count;continue;}
                    $d=&$working[$key];$d['scope'][$table]=true;
                    $due=$this->date($row[$dueField]);
                    if(!$due){$d['missing_dates']+=$count;unset($d);continue;}
                    if($due->gt($today)){unset($d);continue;}
                    if($this->missingStatus($state)){$d['missing_dates']+=$count;unset($d);continue;}
                    $isClosed=in_array($state,array_map('strtolower',$closed),true);
                    if($isClosed&&$due->lt($start)){unset($d);continue;}
                    $d['records']+=$count;
                    $severity=strtolower(trim((string)($row[$severityField]??'')));
                    $weight=in_array($severity,['critical','high','urgent'],true)?3:($severity==='low'?1:2);
                    if(!in_array($severity,['critical','high','urgent','low','medium','moderate'],true))$d['missing_severity']+=$count;
                    $d['weight']+=$weight*$count;
                    if(!$isClosed){$age=(int)$due->diffInDays($today);$d['overdue']+=$due->lt($today)?$count:0;$d['penalty']+=$due->lt($today)?$weight*(1+min(3,$age/30))*$count:0;}
                    $d['metrics']['backlog']['total']+=$count;$d['metrics']['backlog']['sources'][$table]=true;
                    // Today's still-open obligations are not failures before their deadline ends.
                    if($due->gte($start)&&($isClosed||$due->lt($today))){
                        $m=&$d['metrics']['on_time'];$m['total']+=$count;$m['sources'][$table]=true;
                        if($isClosed){$completed=null;foreach($completionFields as $field){$completed=$this->date($row[$field]??null);if($completed)break;}
                            if(!$completed||$completed->gt($today))$m['unknown']+=$count;
                            elseif($completed->lte($due))$m['passed']+=$count;
                        }unset($m);
                    }unset($d);
                }
                $source['available']=true;$departments=$working;$result['unassigned']+=$unassigned;
            }catch(\Throwable $e){Log::warning('Department performance source unavailable',['source'=>$table,'exception'=>get_class($e)]);}
            $result['sources'][]=$source;
        }
        foreach($config['obligations'] as $mapping){
            [$metric,$module,$table,$department,$stage,$dueField,$closed]=$mapping;
            if(!in_array($module,$enabled,true))continue;
            $source=['name'=>$table,'module'=>$module,'available'=>false];
            if(array_diff([$department,$stage,$dueField],$this->columns($table))){$result['sources'][]=$source;continue;}
            $working=$departments;$unassigned=0;
            try{
                foreach($this->query($table,[$department,$stage,$dueField]) as $record){
                    $row=(array)$record;$state=strtolower(trim((string)$row[$stage]));$count=(int)$row['performance_count'];
                    if(in_array($state,['cancelled','canceled','archived','deleted'],true))continue;
                    $key=trim((string)$row[$department]);if(!preg_match('/^[1-9][0-9]*$/D',$key)||!isset($working[$key])){$unassigned+=$count;continue;}
                    $d=&$working[$key];$d['scope'][$table]=true;$due=$this->date($row[$dueField]);
                    if(!$due){$d['missing_dates']+=$count;unset($d);continue;}
                    if($due->lt($start)||$due->gt($today)){unset($d);continue;}
                    if($this->missingStatus($state)){$d['missing_dates']+=$count;unset($d);continue;}
                    $isComplete=in_array($state,array_map('strtolower',$closed),true);
                    if($due->eq($today)&&!$isComplete){unset($d);continue;}
                    $d['records']+=$count;$d['metrics'][$metric]['total']+=$count;$d['metrics'][$metric]['sources'][$table]=true;
                    if($isComplete)$d['metrics'][$metric]['passed']+=$count;
                    unset($d);
                }
                $source['available']=true;$departments=$working;$result['unassigned']+=$unassigned;
            }catch(\Throwable $e){Log::warning('Department performance source unavailable',['source'=>$table,'exception'=>get_class($e)]);}
            $result['sources'][]=$source;
        }
        foreach($departments as &$d){
            $d['coverage']=0;$points=0;$signature=[];
            foreach($config['metrics'] as $key=>$definition){
                $m=&$d['metrics'][$key];$m['label']=$definition['label'];$m['weight']=$definition['weight'];$m['score']=null;
                if($m['total'] && ($key!=='on_time'||$m['unknown']/$m['total']<=0.2)){
                    $m['score']=$key==='backlog'?round(100*max(0,1-$d['penalty']/max(1,$d['weight'])),1):round(100*$m['passed']/$m['total'],1);
                    $d['coverage']+=$definition['weight'];$points+=$definition['weight']*$m['score'];
                    // Only departments with identical measures AND source workflows share a rank.
                    $sources=array_keys($m['sources']);sort($sources);$signature[]=$key.':'.implode(',',$sources);
                }
                $m['sources']=array_keys($m['sources']);unset($m);
            }
            $complete=$d['records']+$d['missing_dates'] ? 100*$d['records']/($d['records']+$d['missing_dates']) : 0;
            $d['completeness']=round($complete,2);
            $d['score']=$complete>=80&&$d['records']>=$config['minimum_records']&&$d['coverage']>=$config['minimum_coverage']?round($points/$d['coverage'],1):null;
            $d['rank']=null;$d['peer_count']=0;$d['cohort']=implode('|',$signature);$d['status']=$d['score']===null?'Insufficient data':'Provisional score';
            $d['change']=null; // Never reconstruct past scores from today's mutable record statuses.
            unset($d['weight'],$d['penalty'],$d['scope']);
        }unset($d);
        $cohorts=[];foreach($departments as $key=>$d)if($d['score']!==null)$cohorts[$d['cohort']][]=$key;
        $number=0;
        foreach($cohorts as $signature=>$keys){
            $number++;usort($keys,function($a,$b)use($departments){return ($departments[$b]['score']<=>$departments[$a]['score'])?:strcasecmp($departments[$a]['name'],$departments[$b]['name']);});
            $last=null;$rank=0;
            foreach($keys as $position=>$key){$d=&$departments[$key];if($last!==$d['score'])$rank=$position+1;$last=$d['score'];$d['peer_count']=count($keys);$d['cohort_label']='Comparison group '.$number;$d['rank']=count($keys)>1?$rank:null;if(count($keys)===1)$d['status']='No comparable peer';unset($d);}
            $result['cohorts'][]=['label'=>'Comparison group '.$number,'ids'=>array_map('intval',$keys),'coverage'=>$departments[$keys[0]]['coverage']];
        }
        $result['departments']=array_values($departments);
        usort($result['departments'],function($a,$b){if($a['score']===null||$b['score']===null)return (($a['score']===null)<=>($b['score']===null))?:strcasecmp($a['name'],$b['name']);return strcmp($a['cohort_label'],$b['cohort_label'])?:($b['score']<=>$a['score'])?:strcasecmp($a['name'],$b['name']);});
        return $result;
    }
}
