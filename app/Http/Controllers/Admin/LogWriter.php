<?php
namespace PMIS\Http\Controllers\Admin;

class LogWriter{
    public function write($file ,$data){
        try{
            $my_file = $file.'.txt';
            $header = "\n".'---------------------------------------------------------------------------'."\n";
            $date = date('Y-m-d')."\n";
            // $handle = fopen('storage/logs/'.$my_file, 'a'); //open file for writing ('w','r','a')...
            $handle = fopen(storage_path('logs/'.$my_file), 'a'); //open file for writing ('w','r','a')...
            fwrite($handle, $header);
            fwrite($handle, $date);
            fwrite($handle, $data);
        }catch(\Exception $e){
            echo ('Cannot open file:  '.$my_file. ' ( message :  )'.$e->getMessage());
        }
        return true;
    }
}
