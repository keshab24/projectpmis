<?php
namespace PMIS\Http\Controllers\Admin;


use Illuminate\Support\Facades\Config;
use mysqli;
use PMIS\Http\Controllers\AdminBaseController;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class Mysql extends AdminBaseController {
    protected $pro_data;
    protected $division;
    public function __construct(){
        parent::__construct();

    }

    public function backups()
    {
        $files = scandir('public/dbbackup/');
        $publicFolder = scandir('public/zip/');
        unset($files[0]);
        unset($files[1]);
        unset($publicFolder[0]);
        unset($publicFolder[1]);
        $this->pro_data['files']=$files;
        $this->pro_data['folders']=$publicFolder;

        return view('admin.backup.index', $this->pro_data);
    }

    public function getBackup($location,$name)
    {
        $file=file_exists("public/".$location."/".$name);
        if($file){
            return response()->download("public/".$location."/".$name);
        }
        return redirect()->back();
    }

    public function index(){

        $para = array(
            'db_host'=> Config::get('database.connections.mysql.host'),  //mysql host
            'db_uname' => Config::get('database.connections.mysql.username'),  //user
            'db_password' => Config::get('database.connections.mysql.password'), //pass
            'db_to_backup' => Config::get('database.connections.mysql.database'), //database name
            'db_exclude_tables' => array(''), //tables to exclude
            'db_backup_path' => 'public/dbbackup/' //tables to exclude
        );
        $file=$this->backup($para);
        return response()->download($file);
    }

    function backup($params)
    {
        header('Content-Type: text/html; charset=utf-8');

        $mtables = array(); $contents = "-- Database: `".$params['db_to_backup']."` --\n";

        $mysqli = new mysqli($params['db_host'], $params['db_uname'], $params['db_password'], $params['db_to_backup']);
        $mysqli->set_charset( 'utf8' );
        if ($mysqli->connect_error) {
            die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
        }

        $results = $mysqli->query("SHOW TABLES");

        while($row = $results->fetch_array()){
            if (!in_array($row[0], $params['db_exclude_tables'])){
                $mtables[] = $row[0];
            }
        }
        $contents.="SET FOREIGN_KEY_CHECKS=0;";
        foreach($mtables as $table){
            $contents .= "-- Table `".$table."` --\n";

            $results = $mysqli->query("SHOW CREATE TABLE ".$table);
            while($row = $results->fetch_array()){
                $contents .= $row[1].";\n\n";
            }

            $results = $mysqli->query("SELECT * FROM ".$table);
            $row_count = $results->num_rows;
            $fields = $results->fetch_fields();
            $fields_count = count($fields);

            $insert_head = "INSERT INTO `".$table."` (";
            for($i=0; $i < $fields_count; $i++){
                $insert_head  .= "`".$fields[$i]->name."`";
                if($i < $fields_count-1){
                    $insert_head  .= ', ';
                }
            }
            $insert_head .=  ")";
            $insert_head .= " VALUES\n";

            if($row_count>0){
                $r = 0;
                while($row = $results->fetch_array()){
                    if(($r % 400)  == 0){
                        $contents .= $insert_head;
                    }
                    $contents .= "(";
                    for($i=0; $i < $fields_count; $i++){
                        $row_content =  str_replace("\n","\\n",$mysqli->real_escape_string($row[$i]));

                        if($row[$i]===NULL){
                            $contents .= "NULL";
                        }elseif($row[$i]==''){
                            $contents .= "''";
                        }else{
                            switch($fields[$i]->type){
                                case 8: case 3:
                                $contents .=  $row_content;
                                break;

                                default:
                                    $contents .= "'". $row_content ."'";
                            }
                        }


                        if($i < $fields_count-1){
                            $contents  .= ', ';
                        }
                    }
                    if(($r+1) == $row_count || ($r % 400) == 399){
                        $contents .= ");\n\n";
                    }else{
                        $contents .= "),\n";
                    }
                    $r++;
                }
            }
        }
        $contents.="SET FOREIGN_KEY_CHECKS=1;";


        if (!is_dir ( $params['db_backup_path'] )) {
            mkdir($params['db_backup_path']);
        }

        $backup_file_name = "sql-backup-".date( "d-m-Y--h-i-s").".sql";

        $fp = fopen($params['db_backup_path'].$backup_file_name ,'w+');
        fwrite($fp, $contents);
        fclose($fp);
        return $params['db_backup_path'].$backup_file_name;
    }

    public function publicBackup()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');

        $date=date('Y-m-d-h-s-m-a');
        $folder='public/zip/'.$date.'/';

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
            if (extension_loaded('zip') === true) {
                    $zip = new ZipArchive();
                    $toBePacked=array('activityFiles','cheifFiles','contractorFiles');
                        foreach ($toBePacked as $item){
                        $source='public/'.$item;
                        $this->pack($source,$folder,$item,$zip);
                    }
            }
        echo 'success';
    }

    function pack($source,$folder,$item,$zip,$test=null){

        if (file_exists($source) === true) {
            $destination=$folder.'/'.$item.'.zip';
            if ($zip->open($destination, ZipArchive::CREATE) === true) {
                $source = realpath($source);
                if (is_dir($source) === true) {
                    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                    foreach ($files as $file) {
                        $file = realpath($file);
                        if (is_dir($file) === true) {
                            $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                        } else if (is_file($file) === true) {
                            $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                        }
                    }
                } else if (is_file($source) === true) {
                    $zip->addFromString(basename($source), file_get_contents($source));
                }
            }
        }

    }

    function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }

}
