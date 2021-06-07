<?php 

class Upload {
    
    
    function uploadFile($upload_name, $tmp_name, $target_dir = '', $filename = null, $prefix = null, $ext = true){      
        // Array with allowed extensions.
        $allow_ext = ['jpeg', 'jpg', 'png', 'gif', 'mp4', 'mp3', 'webm', 'avi'];
        
        if (isset($upload_name) && isset($tmp_name)) {
            // If filename is not set (null), use original file name.
            if ($filename === null) $filename = $upload_name;    
            
            // Get extension of the file.
            $path_parts = pathinfo($upload_name);
            $file_ext = $path_parts['extension'];

            if($ext) { $add_ext = '.'.$path_parts["extension"]; }
            else { $add_ext = null; }
            
            // If extension is in the $allow_ext array, it runs through the statement.
            if (in_array($file_ext, $allow_ext)) {
                               
                // If file already exists it will be overwriten.
                if(file_exists($target_dir.'/'.$filename.$add_ext)) unlink($target_dir.'/'.$filename.$add_ext);
                
                // Set variable $target_file to destination.
                $target_file = $target_dir . basename($filename).$add_ext;
                
                move_uploaded_file($tmp_name, $target_file);
                // Return true if success.
                return true;
            }
            // Return false on failure upload.
            else return false;
        }
        // Return false when no file is given.
        else return false;
    }
    
    function maxImageSize($image, $max_width = 1280, $max_height = 720){ 
        list($width, $height) = getimagesize($image);
        if ($width <= $max_width && $height <= $max_height) {
            return true;
        }
        else return false;        
    }
}
?>