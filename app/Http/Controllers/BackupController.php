<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;
use Storage;
use Log;
use DB;
use Carbon\Carbon;
use Spatie\Backup\Helpers\Format;
use Illuminate\Support\Facades\File;
use App\Upload;
use \ZipArchive;
use RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use Auth;
class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:superadmin,admin');
    }
    
    public function databackup(){
        $admin=DB::table('admins')->get();
    if(count($admin)<=0){
    $type=Auth::user()->type;
    session(['type'=>$type]);
    $clientname="";
    return view('superadmin/lisenceActivation')->with('name',$clientname);
    }
        $disk = Storage::disk('local');

        $files = $disk->files(config('app.name'));
        $backups = [];

        //function to gte the human readable size
        function human_filesize($bytes,$dec=2){
            $size=array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
            $factor=floor((strlen($bytes)-1)/3);

            return sprintf("%.{$dec}f %s",$bytes/(1024 ** $factor),$size[$factor]);
        }
        // make an array of backup files, with their filesize and creation date
        foreach ($files as $k => $f) {
            // only take the zip files into account

            if (substr($f, -4) == '.zip' && $disk->exists($f)) {
                $humanreadablesize=human_filesize($disk->getsize($f));
                $backups[] = [
                    'file_path' => $f,
                    'file_name' => str_replace(config('app.name') . '/', '', $f),
                    'file_size' =>$humanreadablesize,
                    'date' =>Carbon::parse($disk->lastModified($f))->format('d-m-Y'),
                    'time'=>Carbon::parse($disk->lastModified($f))->format('h:i:s A')
                ];
            }
        }
        // reverse the backups, so the newest one would be on top
        $backups = array_reverse($backups);
        $models=DB::table('modenadisable')->First();
        return  view('superadmin/backup')->with('backups',$backups)->with('model',$models);
     
     }

     public function create(Request $request)
     { 

         
         try {
             $app=config('app.name');
            if(!is_dir(storage_path('app/'.$app))){
                Storage::disk('local')->makeDirectory($app);
            }
            $date=Carbon::now()->toDateTimeString();
            $date=Carbon::parse($date);

             // start the backup process
             if($request->db && $request->file){
                 $models=DB::table('modenadisable')->First();
             return redirect()->back()->with("error","Choose one option at a time")->with('model',$models);
             }
             elseif($request->db)
             {
                $dbfile="DB-".$date->format('d-m-Y').'.zip';
                $this->Zip2(storage_path('app/'.$app.'/'.$dbfile));
             }
             else
             {
                 $file="file-".$date->format('d-m-Y').'.zip';
                 $this->Zip1(public_path('/storage'), storage_path('app/'.$app.'/'.$file));
             }
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('success','BackUp Created Sucessfully')->with('model',$models);
         } catch (Exception $e) {
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with("error","Backup Failed")->with('model',$models);
         }
     }

     public function Zip1($source, $destination)
     {

         $zip = new \ZipArchive();
         $zip->open($destination, \ZIPARCHIVE::CREATE|\ZipArchive::OVERWRITE);
         $source = str_replace('\\', '/', realpath($source));
     
         if (is_dir($source) === true)
         {
             $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);
     
             foreach ($files as $file)
             {
                 $file = str_replace('\\', '/', $file);
     
                 // Ignore "." and ".." folders
                 if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ){
                     continue;
                 }
                 $file = realpath($file);
     
                 if (is_dir($file) === true)
                 {
                     $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                 }
                 else if (is_file($file) === true)
                 {
                     $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                 }
             }
         }
         else if (is_file($source) === true)
         {
             $zip->addFromString(basename($source), file_get_contents($source));
         }
     
         return $zip->close();
     }

     public function Zip2($destination){
        $date=Carbon::now()->toDateTimeString();
        $date=Carbon::parse($date);
         $db1=\Config::get('database.connections.mysql.database').$date->format('d-m-Y').'.sql';
        $zip = new \ZipArchive();
        $zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $db=$this->dbbackup(\Config::get('database.connections.mysql.host'),\Config::get('database.connections.mysql.username'), \Config::get('database.connections.mysql.password'), \Config::get('database.connections.mysql.database'));
        $db = str_replace('\\', '/', $db);
        $zip->addFromString($db1,$db);
        return $zip->close();
     }
     public function dbbackup($host,$user,$pass,$name,$tables=false, $backup_name=false)
        { 
            set_time_limit(3000); 
            $con=mysqli_connect($host,$user,$pass,$name);
            mysqli_select_db($con,$name); 
            mysqli_query($con,"SET NAMES 'utf8'");
            $queryTables = mysqli_query($con,'SHOW TABLES'); 
            while($row = $queryTables->fetch_row()) 
            { 
                $target_tables[] = $row[0]; 
            }	
            if($tables !== false) 
            {
                $target_tables = array_intersect( $target_tables, $tables); 
            } 
            $content = "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\nSET time_zone = \"+00:00\";\r\n\r\n\r\n/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n/*!40101 SET NAMES utf8 */;\r\n--\r\n-- Database: `".$name."`\r\n--\r\n\r\n\r\n";
            foreach($target_tables as $table)
            {
                if (empty($table))
                {
                    continue;
                } 
                $result	= mysqli_query($con,'SELECT * FROM `'.$table.'`');  	$fields_amount=$result->field_count;  $rows_num=mysqli_affected_rows($con); 	$res = mysqli_query($con,'SHOW CREATE TABLE '.$table);	$TableMLine=$res->fetch_row(); 
                $content .= "\n\n".$TableMLine[1].";\n\n";   $TableMLine[1]=str_ireplace('CREATE TABLE `','CREATE TABLE IF NOT EXISTS `',$TableMLine[1]);
                for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
                {
                    while($row = $result->fetch_row())	
                    {
                        if ($st_counter%100 == 0 || $st_counter == 0 )	
                        {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                        }
                        $content .= "\n(";    
                        for($j=0; $j<$fields_amount; $j++)
                        {
                            $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                            if (isset($row[$j]))
                            {
                                $content .= '"'.$row[$j].'"' ;
                            } 
                            else
                            {
                                $content .= '""';
                            }
                            if ($j<($fields_amount-1))
                            {
                                $content.= ',';
                            }  
                        }
                        $content .=")";
                        if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                        {
                            $content .= ";";
                        }
                        else
                        {
                            $content .= ",";
                        }
                        $st_counter=$st_counter+1;
                    }
                } $content .="\n\n\n";
            }
            $content .= "\r\n\r\n/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
            $backup_name = $backup_name ? $backup_name : $name.'___('.date('H-i-s').'_'.date('d-m-Y').').sql';
            ob_get_clean(); 
            header('Content-Type: application/octet-stream'); 
            header("Content-Transfer-Encoding: Binary"); 
            header('Content-Length: '. (function_exists('mb_strlen') ? mb_strlen($content, '8bit'): strlen($content)) );   
            header("Content-disposition: attachment; filename=\"".$backup_name."\""); 
            return $content;
        }
        
     public function download($file_name)
     {
         $file = config('app.name') . '/' . $file_name;
         $disk = Storage::disk('local');
         if ($disk->exists($file)) {
             ob_end_clean();
             $fs = Storage::disk('local')->getDriver();
             $stream = $fs->readStream($file);
 
             return \Response::stream(function () use ($stream) {
                 fpassthru($stream);
             }, 200, [
                 "Content-Type" => $fs->getMimetype($file),
                 "Content-Length" => $fs->getSize($file),
                 "Content-disposition" => "attachment; filename=\"" . basename($file) . "\"",
             ]);
         } else {
             abort(404, "The backup file doesn't exist.");
         }
     }

         public function delete($file_name)
    {
        $disk = Storage::disk('local');
        if ($disk->exists(config('app.name') . '/' . $file_name)) {
            $disk->delete(config('app.name') . '/' . $file_name);
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('success','BackUp Deleted Sucessfully')->with('model',$models);
        } else {
            abort(404, "The backup file doesn't exist.");
        }
    }

    public function restoredb(Request $request)
    {
        if($request->get('rdb')){
        if($request->hasFile('resdb')){
        $DB=$request->get('dbname'); 
        $filename = $request->resdb;
        $username=\Config::get('database.connections.mysql.username');
        $password=\Config::get('database.connections.mysql.password');
        $hostname=\Config::get('database.connections.mysql.host');
        $appDB=\Config::get('database.connections.mysql.database');
        
    try{
    $connection = mysqli_connect($hostname,$username,$password); 
    }
    catch(Exception $e)
    {
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('error','Connection to the DataBase could not be established due to wrong credentials.')->with('model',$models);
         
    }

    if($DB!=$appDB){
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('model',$models)->with('error','Given DataBase Name Does not match with Application DataBase Name, Please give the DataBase name which you have given at the time of installation.');
    }
    $selectdb = mysqli_select_db($connection,$DB);  
    if(!$selectdb){ 
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('error','Given DataBase Name Does not exists, Please give the DataBase name which you have given at the time of installation.');
       
    }

    // backup_tables($hostname,$username,$password,$DB);
    $query="DROP DATABASE {$DB}";
    mysqli_query($connection,$query);
    $sql="CREATE DATABASE {$DB}";
    mysqli_query($connection,$sql);

    //declaring importtable function
   $connection = mysqli_connect($hostname,$username,$password); 
    if(mysqli_connect_errno())
    {
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('model',$models)->with('error','Connection to the DataBase could not be established due to wrong credentials.');
        
    }
   
    $selectdb = mysqli_select_db($connection,$DB);  
    if(!$selectdb){
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('error','Given DataBase Name Does not exists, Please give the DataBase name which you have given at the time of installation.');
    }
    $templine = '';
    if(!file($filename)){
        $models=DB::table('modenadisable')->First();
        return redirect()->back()->with('model',$models)->with('error','Given .SQL file does not exists');
       
    }

    $output='';
    $count=0;
    $file_data=file($filename);
    foreach($file_data as $row){
        $str_chr=substr(trim($row),0,2);
        if($str_chr !='--' || $str_chr !='/*' || $str_chr !='//' || $row  !=''){
            $output=$output.$row;
            $end_chr=substr(trim($row),-1,1);
            if($end_chr==";"){
                if(!mysqli_query($connection,$output)){
                    print('Error performing query \'<strong>' . $templine . '\': ' . mysqli_error($connection) . '<br /><br />');
                    $count=$count+1;
                }
                $output='';

            }
        }
    }
}
$models=DB::table('modenadisable')->First();
return redirect()->back()->with('success','DataBase Restored Successfully');
}
}

public function restorefile(Request $req){
if(sizeof($_FILES) > 0){
    $dir=public_path();
    $fileUploader = new Upload($req->file('files'),$dir,$req->hd);
}
$models=DB::table('modenadisable')->First();
return redirect()->back()->with('model',$models)->with('success','Files Restored Successfully');

}

}