<?php

session_start();
############ Configuration ##############
$config["image_max_size"]               = 500; //Maximum image size (height and width)
$config["thumbnail_size"]               = 200; //Thumbnails will be cropped to 200x200 pixels
$config["thumbnail_prefix"]             = ""; //Normal thumb Prefix
$config["destination_folder"]           = '../img/user_images/'; //upload directory ends with / (slash)
$config["thumbnail_destination_folder"] = '../img/user_images/thumbnails/'; //upload directory ends with / (slash)
$config["upload_url"]                   = "img/user_images/"; 
$config["quality"]                      = 90; //jpeg quality


if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    exit;  //try detect AJAX request, simply exist if no Ajax
}

if(!isset($_FILES['__files']) || !is_uploaded_file($_FILES['__files']['tmp_name'][0])){
   die('Image file is Missing!');
}

//count total files in array
$file_count = count($_FILES["__files"]["name"]);

if($file_count > 0){ //there are more than one file? no problem let's handle multiple files


    for ($x = 0; $x < $file_count; $x++){   //Loop through each uploaded file
    
        //if there's file error, display it
        if ($_FILES["__files"]['error'][$x] > 0) { 
            print get_upload_error($x);
            exit;
        }

        //Get image info from a valid image file
        $im_info = getimagesize($_FILES["__files"]["tmp_name"][$x]);
        if($im_info){
            $im["image_width"]  = $im_info[0]; //image width
            $im["image_height"] = $im_info[1]; //image height
            $im["image_type"]   = $im_info['mime']; //image type
        }else{
            die("Make sure image <b>".$_FILES["__files"]["name"][$x]."</b> is valid image file!");
        }
        
        //create image resource using Image type and set the file extension
        switch($im["image_type"]){
            case 'image/png':
                $img_res =  imagecreatefrompng($_FILES["__files"]["tmp_name"][$x]);
                $file_extension = ".jpg";
                break;
            case 'image/gif':
               $img_res = imagecreatefromgif($_FILES["__files"]["tmp_name"][$x]);     
               $file_extension = ".jpg";
               break;
            case 'image/jpeg': 
            case 'image/pjpeg':
                $img_res = imagecreatefromjpeg($_FILES["__files"]["tmp_name"][$x]);
                $file_extension = ".jpg";
                break;
            default:
                $img_res = 0;
        }
        
        //set our file variables 
        $file_name =  $_SESSION['student_number']; //unique id for random filename
        $new_file_name = $file_name . $file_extension; 
        $destination_file_save = $config["destination_folder"] . $new_file_name; //file path to destination folder
        $destination_thumbnail_save = $config["thumbnail_destination_folder"] . $config["thumbnail_prefix"]. $new_file_name; //file path to destination thumb folder

        if($img_res){
            ###### resize Image ########
            //Construct a proportional size of new image
            $image_scale    = min($config["image_max_size"]/$im["image_width"], $config["image_max_size"]/$im["image_height"]);
            $new_width      = ceil($image_scale * $im["image_width"]);
            $new_height     = ceil($image_scale * $im["image_height"]);
    
            //Create a new true color image
            $canvas  = imagecreatetruecolor($new_width, $new_height);
            $resample = imagecopyresampled($canvas, $img_res, 0, 0, 0, 0, $new_width, $new_height, $im["image_width"], $im["image_height"]);
            if($resample){
                $save_image = save_image_file($im["image_type"], $canvas, $destination_file_save, $config["quality"]); //save image
                if($save_image){
                    //print '<br><img style="border: 10px solid #222d32;" src="'.$config["upload_url"] . $new_file_name. '" />'; //output image to browser
                }
            }
            
            if(is_resource($canvas)){ 
              imagedestroy($canvas);  //free any associated memory 
            } 

            
            ###### Generate Thumbnail ########
            
            //Offsets 
            if( $im["image_width"] > $im["image_height"]){
                $y_offset = 0;
                $x_offset = ($im["image_width"] - $im["image_height"]) / 2;
                $s_size     = $im["image_width"] - ($x_offset * 2);
            }else{
                $x_offset = 0;
                $y_offset = ($im["image_height"] - $im["image_width"]) / 2;
                $s_size = $im["image_height"] - ($y_offset * 2);
            }
            
            //Create a new true color image
            $canvas = imagecreatetruecolor($config["thumbnail_size"], $config["thumbnail_size"]); 
            $resample = imagecopyresampled($canvas, $img_res, 0, 0, $x_offset, $y_offset, $config["thumbnail_size"], $config["thumbnail_size"], $s_size, $s_size);
            if($resample){
                $save_image = save_image_file($im["image_type"], $canvas, $destination_thumbnail_save, $config["quality"] );
                if($save_image){
                    //print '<img src="'.$config["upload_url"] . $config["thumbnail_prefix"]. $new_file_name. '" />';
                }
            }
            
            if(is_resource($canvas)){ 
              imagedestroy($canvas);  //free any associated memory 
            } 
            
            
        }
        
    }
}
 
 //funcion to save image file
function save_image_file($image_type, $canvas, $destination, $quality){
    switch(strtolower($image_type)){
        case 'image/png': 
            return imagepng($canvas, $destination); //save png file
        case 'image/gif': 
            return imagegif($canvas, $destination); //save gif file                
        case 'image/jpeg': case 'image/pjpeg': 
            return imagejpeg($canvas, $destination, $quality);  //save jpeg file
        default: 
            return false;
    }
}

function get_upload_error($err_no){
    switch($err_no){
        case 1 : return 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
        case 2 : return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
        case 3 : return 'The uploaded file was only partially uploaded.';
        case 4 : return 'No file was uploaded.';
        case 5 : return 'Missing a temporary folder. Introduced in PHP 5.0.3';
        case 6 : return 'Failed to write file to disk. Introduced in PHP 5.1.0';
    }
}
