<?php
    
class Utilities {
    
    /**
     * All regular files of a distinct directory
     */
    public static function list_files ($directory = "") {
        
        $files = Array();

        $file_pointer = opendir($directory);
        while ( $file = readdir($file_pointer) ) {
            if ( strstr($file, '.') != $file && !is_dir($file) )
                $files[] = $file;
        }
        closedir($file_pointer);
        
        return $files;
    }
}

define("BASE_URL", str_replace("index.php", "", $_SERVER['SCRIPT_NAME']));

?>
