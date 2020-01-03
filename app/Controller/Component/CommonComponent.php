<?php
/**
 * Custom component
 *
 * PHP 5
 *
 * Created By         :Navdeep kaur
 * Date Of Creation   : 23 Oct 2013
 * 
 */

App::uses('Component', 'Controller');

/**
 * Custom component
 */
class CommonComponent extends Component {
    
/**
 * This component uses the component
 *
 * @var array
 */    
    var $components = array('Cookie','Session','Email','Upload','Easyphpthumbnail');
    
/*
 * Function to generate the random password
 */
    public function getRandPass() {
        
        // Array Declaration
        $pass = array();
        
        // Variable declaration
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for($i = 0; $i < 8; $i++){
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    
/**
 * Upload Original Image
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        image_upload
 * @param         $file, $path, $folder_name, $thumb, $multiple
 * @return        $filename or $err_type
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function upload_image($file, $path, $folder_name, $thumb = false, $multiple = array()){
        
        // Variable containing File type
        $extType = $file['type'];
         
        // Variable containing extension in lowercase 
        $ext = strtolower($extType);				
         
        // Condition checking File extension
        if($ext=='image/jpg' || $ext=='image/png' || $ext=='image/jpeg' || $ext=='image/gif'){					
         
            // Condition checking File size
            if($file['size'] <= 10485760){					
            
                // Filename 
                $filename = time().'_'.$file['name'];
                
                // Folder path
                $folder_url = WWW_ROOT.$path.'/'.$folder_name;
                
                // Condition checking File exist or not 
                if (!file_exists($folder_url.'/'.$filename)){
                   
                    // create full filename
                    $full_url = $folder_url.'/'.$filename;			
                  
                    // upload the file					
                    $success = move_uploaded_file($file['tmp_name'], $full_url);
                
                    //
                    if($thumb){
                        // If multiple folder upload required then pass TRUE as last parameter
                        $this->upload_thumb_image($filename, $path, $folder_name, $multiple);
                    }
                     
                    return $filename;
                }else{
                    return 'exist_error';
                }
            }else{
                return 'size_mb_error';
            }
        }else{
            return 'type_error';
        }
    }
   
/**
 * Upload Thumb Image
 * @author       Navdeep Kaur
 * @copyright     smartData Enterprise Inc.
 * @method        upload_thumb_image
 * @param         $filename, $path, $folder_name, $multiple
 * @return        void
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function upload_thumb_image($filename, $path, $folder_name, $multiple = array()){
        
        // image path from where pic taken
        $dircover = str_replace(chr(92),chr(47),getcwd()).'/'.$path.'/'.$folder_name.'/'.$filename;
        if(!empty($multiple) && count($multiple)> 0){
        	foreach($multiple as $result){
        		$this->Easyphpthumbnail-> Thumblocation = str_replace(chr(92),chr(47),getcwd()).'/'.$path.'/'.$result['folder_name'].'/';
        		$this->Easyphpthumbnail-> Thumbheight = $result['height'];
        		$this->Easyphpthumbnail-> Thumbwidth =  $result['width'];
        		$this->Easyphpthumbnail-> Createthumb($dircover,'file');
        	}
        }
    }    
    
    
/**
 * Handle image errors
 * @author        Anuj Kumar
 * @copyright     smartData Enterprise Inc.
 * @method        is_image_error
 * @param         $image_name
 * @return        error msg
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function is_image_error($image_name = null){
        $errmsg = '';
        switch($image_name){
            case 'exist_error':
                $errmsg = 'File already exist.';
                break;
            
            case 'size_mb_error':
                $errmsg = 'Only mb of file is allowed to upload.';
                break;
            
            case 'type_error':
                $errmsg = 'Only JPG, JPEG, PNG & GIF are allowed.';
                break;
        }
        return $errmsg;
    }
/**
 * Delete image
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        delete_image
 * @param         $image_name, $path, $thumb_path
 * @return        void
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function delete_image($imagename = null, $path = null, $folder_name = null, $thumb = false, $multiple = array()){
        
        if(!empty($path)){
            $full_path = WWW_ROOT.$path.'/'.$folder_name.'/'.$imagename;
            if(file_exists($full_path)){
                unlink($full_path);
            }
            
            if($thumb){
                if(!empty($multiple) && count($multiple)> 0){
                    foreach($multiple as $result){
                        $full_thumb_path = WWW_ROOT.$path.'/'.$result['folder_name'].'/'.$imagename;
                        if(file_exists($full_thumb_path)){
                            unlink($full_thumb_path);
                        }
                    }
                }
            }
            
        }
    }
    

    
/**
 * Upload Video
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        upload_video
 * @param         $file, $path
 * @return        $filename or $err_type
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function upload_video($file, $path){
        
        // Variable containing File type
        $extType = end(explode('.',$file['name']));
         
        // Variable containing extension in lowercase 
        $ext = strtolower($extType);
        
        // Condition checking File extension
        if($ext=='mov' || $ext=='avi' || $ext=='wmv' || $ext=='dat' || $ext=='mpeg' || $ext=='mpg' || $ext=='flv' || $ext=='mp4' || $ext=='mp2'){
            
            // Condition checking File size
            if($file['size'] <= 10485760){
                
                // Array Declaration
                $arrVideo = array();
                
                // Filename without extension
                $filename_without_ext = preg_replace('/\.[a-z0-9]+$/i','',$file['name']);
                
                // New filename
                $new_filename = time().'_'.$filename_without_ext;
                
                // Filename 
                $original_filename = $new_filename.'.'.$ext;
                $converted_filename = $new_filename.'.flv';
                $thumb_filename = $new_filename.'.jpg';
                
                // Folder path
                $path_original_video = WWW_ROOT.$path.'/'.$original_filename;
                $path_converted_video = WWW_ROOT.$path.'/'.$converted_filename;
                $path_converted_video_thumb = WWW_ROOT.$path.'/thumb/'.$thumb_filename;
                
                // Condition checking File exist or not 
                if (!file_exists($path_original_video)){
                   
                    // create full filename
                    $full_url = $path_original_video;			
                  
                    // upload the file					
                    if(move_uploaded_file($file['tmp_name'], $full_url)){
                 
                        // The first this we need to do is convert the video
                        $this->VideoEncoder->convert_video($path_original_video, $path_converted_video, 480, 360);
                        
                        // Then we need to set the buffer on the converted video
                        $this->VideoEncoder->set_buffering($path_converted_video);
                       
                        // We can also grab a screenshot from the video as a jpeg and store it for future use.
                        $this->VideoEncoder->grab_image($path_converted_video, $path_converted_video_thumb);
                        
                        if($ext != 'flv'){
                            // Finally we can delete the original video
                            $this->VideoEncoder->remove_uploaded_video($path_original_video);
                        }
                            
                        $arrVideo = array('0'=>$converted_filename,'1'=>$thumb_filename);
                        return $arrVideo;
                    
                    }else{
                        return 'some_error';   
                    }
                }else{
                    return 'exist_error';
                }
            }else{
                return 'size_mb_error';
            }
        }else{
            return 'type_error';
        }
    }
    
/**
 * Handle image errors
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        is_video_error
 * @param         array()
 * @return        error msg
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function is_video_error($arr = array()){
        $errmsg = '';
        if(!empty($arr) && count($arr) > 0){
            switch($arr[0]){
                case 'some_error':
                    $errmsg = 'Some error occured while uploading video. Please try again.';
                    break;
                
                case 'exist_error':
                    $errmsg = 'File already exist.';
                    break;
                
                case 'size_mb_error':
                    $errmsg = 'Only mb of file is allowed to upload.';
                    break;
                
                case 'type_error':
                    $errmsg = 'Only JPG, JPEG, PNG & GIF are allowed.';
                    break;
            }
        }
        return $errmsg;
    }
    
/**
 * Upload Document
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        upload_document
 * @param         $file, $path
 * @return        $filename or $err_type
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function upload_document($file, $path){
        
        // Variable containing File type
        $extType = end(explode('.',$file['name']));
        
        // Variable containing extension in lowercase 
        $ext = strtolower($extType);
        
        // Condition checking File extension
        if($ext=='xls' || $ext=='doc' || $ext=='docx' || $ext=='pdf' || $ext=='txt'){
            
            // Condition checking File size
            if($file['size'] <= 10485760){                
                // Filename 
                $filename = time().'_'.$file['name'];
                
                // Folder path
                $folder_url = WWW_ROOT.$path.'/'.$filename;
                
                // Condition checking File exist or not 
                if (!file_exists($folder_url)){
                   
                    // create full filename
                    $full_url = $folder_url;			
                  
                    // upload the file					
                    if(move_uploaded_file($file['tmp_name'], $full_url)){
                        return $filename;
                    }else{
                        return 'some_error';   
                    }
                }else{
                    return 'exist_error';
                }
            }else{
                return 'size_mb_error';
            }
        }else{
            return 'type_error';
        }
    }
    
/**
 * Handle image errors
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        is_document_error
 * @param         $document_name
 * @return        error msg
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function is_document_error($document_name = null){
        $errmsg = '';
        switch($document_name){
            case 'some_error':
                $errmsg = 'Some error occured while uploading document. Please try again.';
                break;
            
            case 'exist_error':
                $errmsg = 'File already exist.';
                break;
            
            case 'size_mb_error':
                $errmsg = 'Only 10 mb of file is allowed to upload.';
                break;
            
            case 'type_error':
                $errmsg = 'Only TXT, PDF, DOC, DOCX & XLS are allowed.';
                break;
        }
        return $errmsg;
    }
    
/**
 * Delete image
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        delete_image
 * @param         $image_name, $path, $thumb_path
 * @return        void
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function delete_document($filename, $path){
        if(!empty($filename) && !empty($path)){
            $full_path = WWW_ROOT.$path.'/'.$filename;
            if(file_exists($full_path)){
                unlink($full_path);
            }
        }
    }
    
/**
 * Download file
 * @author       Navdeep kaur
 * @copyright     smartData Enterprise Inc.
 * @method        download_file
 * @param         $filename, $path
 * @return        void
 * @since         version 0.0.1
 * @version       0.0.1 
 */
    public function download_file($filename, $path){    
        
        // Variable Declaration
        $fullPath = $path.'/'.$filename;
        if($fd = fopen($fullPath, 'r')) {
            $fsize = filesize($fullPath);
            $path_parts = pathinfo($fullPath);
            $ext = strtolower($path_parts["extension"]);
            switch ($ext) {
                case 'xls':
                case 'doc':
                case 'docx':
                    // add here more headers for diff. extensions
                    header("Content-type: application/doc");
                    // use 'attachment' to force a download
                    header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); 
                    break;
                
                default;
                    header("Content-type: application/octet-stream");
                    header("Content-Disposition: filename=\"".$path_parts["basename"]."\"");
            }
            header("Content-length: $fsize");
            header("Cache-control: private"); //use this to open files directly
            while(!feof($fd)) {
                $buffer = fread($fd, 2048);
                echo $buffer;
            }
        }
        fclose ($fd);
        exit;
    }

    /* Change the space into underscore */
    function stringConvertUscoreToSpace($getName = null)
    {
        $getName = strtolower(str_replace('_',' ',$getName));
        return $getName;
    }
    
    /* Report Management */
    function getReport($getName = null)
    {
        $reportArray =  array('1' => 'Order Master','2'=>'Order Detail');
        return $reportArray;
    }
    
    /* Get State List */
    function getStateList()
	{
		return $statelist = array("AL" => "Alabama","AK" => "Alaska","AZ" => "Arizona","AR" => "Arkansas","AS" => "American Samoa","CA" => "California","CO" => "Colorado","CT" => "Connecticut","DE" => "Delaware","DC" => "District of Columbia","FL" => "Florida","GA" => "Georgia","GU" => "Guam","HI" => "Hawaii","ID" => "Idaho","IL" => "Illinois","IN" => "Indiana","IA" => "Iowa","KS" => "Kansas","KY" => "Kentucky","LA" => "Louisiana","ME" => "Maine","MD" => "Maryland","MA" => "Massachusetts","MI" => "Michigan","MN" => "Minnesota","MS" => "Mississippi","MO" => "Missouri","MT" => "Montana","NE" => "Nebraska","NV" => "Nevada","NH" => "New Hampshire","NJ" => "New Jersey","NM" => "New Mexico","NY" => "New York","NC" => "North Carolina","ND" => "North Dakota","MP" => "Northern Marianas Islands","OH" => "Ohio","OK" => "Oklahoma","OR" => "Oregon","PA" => "Pennsylvania","PR" => "Puerto Rico","RI" => "Rhode Island","SC" => "South Carolina","SD" => "South Dakota","TN" => "Tennessee","TX" => "Texas","UT" => "Utah","VT" => "Vermont","VA" => "Virginia","VI" => "Virgin Islands","WA" => "Washington","WV" => "West Virginia","WI" => "Wisconsin","WY" => "Wyoming");
	}
    
    
    
}