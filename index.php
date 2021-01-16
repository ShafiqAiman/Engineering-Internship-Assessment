<?php

ini_set('display_errors',0);

if(isset($_POST["btn_zip"]))  //If there is input of zip file
 {  
      $displayedimages = NULL; //to be used to display images 
      if(!empty($_FILES['zip_file']['name']))  //if the file is exist
      {  
           $file_name = $_FILES['zip_file']['name'];  
           $array = explode(".", $file_name);  
           $name = $array[0];  
           $ext = $array[1];  
           if($ext == 'zip')  //if the file extension is zip file, then it will proceed else an error will be shown.
           { 
                if(is_dir("upload")===false)
                {
                    mkdir("upload"); // if upload folder is not exist, it will create one
                }
                $path = 'upload/';  
                $location = $path . $file_name;  
                if(move_uploaded_file($_FILES['zip_file']['tmp_name'], $location))  
                {  
                     $zip = new ZipArchive;  
                     if($zip->open($location))  
                     {  
                          $zip->extractTo($path);  //The zip file is extracted to upload folder
                          $zip->close();  
                     }  
                     $files = scandir($path . $name);  
                     //$name is extract folder from zip file  
                     foreach($files as $file)  
                     {  
                          $tmp = explode(".", $file);
                          $file_ext = end($tmp);  
                          $allowed_ext = ['jpeg','jpg','png']; 
                          if(in_array($file_ext, $allowed_ext))  
                          {  
                               $new_name = md5(rand()).'.' . $file_ext; //name the photos with random number to avoid clashes 
                               $displayedimages .= '<div class="col-md-6"><div style="padding:16px; border:1px solid #CCC;"><img src="upload/'.$new_name.'" width="300" height="240" /></div></div>';  
                               copy($path.$name.'/'.$file, $path . $new_name);  
                               unlink($path.$name.'/'.$file);  
                          }       
                     }    
                     rmdir($path . $name);  
                }  
           }else{
                $displayedimages= "This file is not zip file";
           }  
      }  
 }  

?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Engineering Internship Assessment</title>
  <meta name="description" content="The HTML5 Herald" />
  <meta name="author" content="Digi-X Internship Committee" />

  <link rel="stylesheet" href="style.css?v=1.0" />
  <link rel="stylesheet" href="custom.css?v=1.0" />

</head>

<body>

    <div class="top-wrapper">
        <img src="https://assets.website-files.com/5cd4f29af95bc7d8af794e0e/5cfe060171000aa66754447a_n-digi-x-logo-white-yellow-standard.svg" alt="digi-x logo" height="70" />
        <h1>Engineering Internship Assessment</h1>
    </div>

    <br />  
           <div class="container" style="width:500px;">  
                <br /> <br /> <br />
                <form method="post" enctype="multipart/form-data">  
                     <label>Please Select Zip File</label>  
                     <input type="file" name="zip_file" />  
                     <br />  
                     <input type="submit" name="btn_zip" class="btn btn-info" value="Upload" />  
                </form>

    <!-- DO NO REMOVE CODE STARTING HERE -->
    <div class="display-wrapper">
        <h2 style="margin-top:51px;">My images</h2>
        <div class="append-images-here">
            <p>No image found. Your extracted images should be here.</p>
            <!-- THE IMAGES SHOULD BE DISPLAYED INSIDE HERE -->
        </div>
    </div>
    <!-- DO NO REMOVE CODE UNTIL HERE -->
    <?php  
                if(isset($displayedimages))  
                {  
                     echo $displayedimages;  
                }  
                ?>

</body>
</html>