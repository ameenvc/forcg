<?php

namespace App\Http\Controllers\Admin\SystemManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModenaDisable;
use App\Models\SystemBackup;
use Storage;
use Carbon\Carbon;

class BackupController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:admin', 'mfa']);
    }

    public function index()
    {
        $models = Modenadisable::first();
        $databaseBackups = SystemBackup::with('admin')->where('backup_type', 'database')->orderBy('id', 'desc')->get();
        $fileBackups = SystemBackup::with('admin')->where('backup_type', 'files')->orderBy('id', 'desc')->get();
        return view('admin.system_settings.backup', compact('databaseBackups', 'fileBackups'))->with('model', $models);
    }

    public function databaseBackup(Request $request)
    {
        // 'file_size',
        // 'error_message',
        $systemBackup = new SystemBackup();
        $systemBackup->admin_id = auth()->id();
        $systemBackup->email_id = auth()->user()->email;
        $systemBackup->backup_type = 'database';
        $systemBackup->status = 'started';
        $systemBackup->started_at = Carbon::now();

        try {
            $app=config('app.name');

            if(!is_dir(storage_path('app/admin/'.$app))){
                Storage::disk('local')->makeDirectory('admin/'.$app);
            }

            $date = Carbon::now();
            $dbfile = "DB-" . $date->format('d-m-Y') . "_" . uniqid() . ".zip";
            $systemBackup->filename = $dbfile;
            $systemBackup->filepath = 'admin/'.$app.'/'.$dbfile;
            $systemBackup->save();

            $fileSize = $this->Zip2(storage_path('app/admin/'.$app.'/'.$dbfile));
            $systemBackup->file_size = $fileSize;
            $systemBackup->status = 'completed';
            $systemBackup->completed_at = Carbon::now();
            $systemBackup->save();
            return response()->json(['status' => true, 'message' => 'BackUp Created Sucessfully']);
        } catch (Exception $e) {
            $systemBackup->status = 'failed';
            $systemBackup->completed_at = Carbon::now();
            $systemBackup->save();

            return response()->json(['status' => false, 'message' => 'BackUp Failed']);
        }
    }

    public function filesBackup(Request $request)
    {
        $systemBackup = new SystemBackup();
        $systemBackup->admin_id = auth()->id();
        $systemBackup->email_id = auth()->user()->email;
        $systemBackup->backup_type = 'files';
        $systemBackup->status = 'started';
        $systemBackup->started_at = Carbon::now();

        try {
            $app=config('app.name');

            if(!is_dir(storage_path('app/admin/'.$app))){
                Storage::disk('local')->makeDirectory('admin/'.$app);
            }

            $date = Carbon::now();
            $file = "File-" . $date->format('d-m-Y') . "_" . uniqid() . ".zip";

            $systemBackup->filename = $file;
            $systemBackup->filepath = 'admin/'.$app.'/'.$file;
            $systemBackup->save();

            $fileSize = $this->Zip1(public_path('/storage'), storage_path('app/admin/'.$app.'/'.$file));

            $systemBackup->file_size = $fileSize;
            $systemBackup->status = 'completed';
            $systemBackup->completed_at = Carbon::now();
            $systemBackup->save();

            return response()->json(['status' => true, 'message' => 'BackUp Created Sucessfully']);
        } catch (Exception $e) {
            $systemBackup->status = 'failed';
            $systemBackup->completed_at = Carbon::now();
            $systemBackup->save();

            return response()->json(['status' => false, 'message' => 'BackUp Failed']);
        }
    }

    public function download($id)
    {
        $backup = SystemBackup::findOrFail($id);
        $file = $backup->filepath;
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

    public function delete(Request $request)
    {
        $backup = SystemBackup::findOrFail($request->id);
        Storage::disk('public')->delete($backup->filepath);
        $backup->delete();
        return response()->json(['status' => 1, 'message' => 'deleted file successfully']);
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

        $result = $zip->close();
        if ($result) {
            $size = filesize($destination);
            return $size;
        }
        return false;
    }

    public function Zip2($destination)
    {
        $date = Carbon::now();

        $db1 = config('database.connections.mysql.database'). '_' . $date->format('d-m-Y') . '.sql';

        $zip = new \ZipArchive();

        $result = $zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        if ($result !== true) {
            throw new \Exception(
                'Unable to create ZIP file. Error code: ' . $result
            );
        }

        $db = $this->dbbackup(
            config('database.connections.mysql.host'),
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database')
        );

        if ($db === false || $db === null) {
            throw new \Exception('Database backup returned empty data.');
        }

        $zip->addFromString($db1, $db);

        if (!$zip->close()) {
            throw new \Exception('Unable to close ZIP file.');
        }
        $size = filesize($destination);

        return $size;
    }

    public function dbbackup(
        $host,
        $user,
        $pass,
        $name,
        $tables = false,
        $backup_name = false
    ) {
        set_time_limit(3000);

        $con = mysqli_connect($host, $user, $pass, $name);

        if (!$con) {
            throw new \Exception(
                'Database connection failed: ' . mysqli_connect_error()
            );
        }

        mysqli_set_charset($con, 'utf8mb4');

        $queryTables = mysqli_query($con, 'SHOW TABLES');

        if (!$queryTables) {
            throw new \Exception(
                'Unable to get database tables: ' . mysqli_error($con)
            );
        }

        $target_tables = [];

        while ($row = mysqli_fetch_row($queryTables)) {
            $target_tables[] = $row[0];
        }

        // If specific tables were provided
        if ($tables !== false) {
            $target_tables = array_intersect(
                $target_tables,
                $tables
            );
        }

        $content =
            "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\r\n" .
            "SET time_zone = \"+00:00\";\r\n\r\n" .

            "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\r\n" .
            "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\r\n" .
            "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\r\n" .
            "/*!40101 SET NAMES utf8mb4 */;\r\n\r\n" .

            "--\r\n" .
            "-- Database: `" . $name . "`\r\n" .
            "--\r\n\r\n";

        foreach ($target_tables as $table) {

            if (empty($table)) {
                continue;
            }

            /*
            * Get CREATE TABLE statement
            */
            $res = mysqli_query(
                $con,
                "SHOW CREATE TABLE `" . $table . "`"
            );

            if (!$res) {
                throw new \Exception(
                    'Unable to get structure for table ' .
                    $table .
                    ': ' .
                    mysqli_error($con)
                );
            }

            $tableData = mysqli_fetch_row($res);

            $createTable = $tableData[1];

            $createTable = str_ireplace(
                'CREATE TABLE `',
                'CREATE TABLE IF NOT EXISTS `',
                $createTable
            );

            $content .= "\r\n\r\n";
            $content .= $createTable . ";";
            $content .= "\r\n\r\n";


            /*
            * Get table data
            */
            $result = mysqli_query(
                $con,
                "SELECT * FROM `" . $table . "`"
            );

            if (!$result) {
                throw new \Exception(
                    'Unable to read table ' .
                    $table .
                    ': ' .
                    mysqli_error($con)
                );
            }

            $fields_amount = mysqli_num_fields($result);
            $st_counter = 0;

            while ($row = mysqli_fetch_row($result)) {

                if ($st_counter % 100 == 0) {

                    if ($st_counter > 0) {
                        $content .= ";";
                    }

                    $content .=
                        "\r\nINSERT INTO `" .
                        $table .
                        "` VALUES";
                }

                $content .= "\r\n(";

                for ($j = 0; $j < $fields_amount; $j++) {

                    if ($row[$j] === null) {

                        $content .= "NULL";

                    } else {

                        $value = addslashes($row[$j]);

                        $value = str_replace(
                            "\n",
                            "\\n",
                            $value
                        );

                        $content .= '"' . $value . '"';
                    }

                    if ($j < ($fields_amount - 1)) {
                        $content .= ",";
                    }
                }

                $content .= ")";

                $st_counter++;
            }

            if ($st_counter > 0) {
                $content .= ";";
            }

            $content .= "\r\n\r\n";
        }

        $content .=
            "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\r\n" .
            "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\r\n" .
            "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";

        mysqli_close($con);

        return $content;
    }
}
