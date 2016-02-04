<?php
/*
* DO NOT REMOVE THIS LINE
* Author : Pritesh Gandhi
*/
?>
<?php 
 if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name       = $_FILES['image']['name'];  
    $temp_name  = $_FILES['image']['tmp_name']; 
    
    if(isset($name)){
        if(!empty($name)){      
            $location = 'uploads/';  

            // Save the original file in the begining and replace with cropped version later
            if(move_uploaded_file($temp_name, $location.$name)){
                
                // Set the default dimentions for the new image
                $targ_w = $targ_h = 150;
				$jpeg_quality = 90;

				// Source where the file is saved on the server
				$src = $location.$name;

				// Create image from the provided path
				$img_r = imagecreatefromjpeg($src);
				
				// Create an empty image frame to generate the new image
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

				// Copy the cropped part from the source image to destination image
				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);

				// Rotate the image according to the sent value of d
				// Note that imagerotate function rotates in anticlockwise 
				// direction so we have to negate d
				$dst_r = imagerotate($dst_r, -$_POST['d'], 0);

				// Uncomment this line if you want to save the file to the server
				// This line saves or rather replaces the original image with the cropped image
				//imagejpeg($dst_r,$src,$jpeg_quality);

				// Comment these two lines if you have uncommented the above line
				header('Content-type: image/jpeg');
				imagejpeg($dst_r,null,$jpeg_quality);

				exit;
            }
        }       
    }  
    else {
        echo 'You should select a file to upload !!';
    }
}

?>

<!DOCTYPE html>
<html>
	<head>
  		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<link rel="stylesheet" href="libs\bootstrap\css\bootstrap.min.css"></link>
		<script src="libs\jquery-1.12.0.min.js"></script>
		<script src="libs\bootstrap\js\bootstrap.min.js"></script>
		<script src="libs\jQueryRotate.js"></script>
		<script src="libs/JCrop/js/jquery.Jcrop.js"></script>	
		<link rel="stylesheet" href="libs/JCrop/css/jquery.Jcrop.css" type="text/css" />
		<link rel="stylesheet" href="css/style.css"></link>		
		<script src="js/main.js"></script>
	</head>
	<body>
		<form action="index.php" method="post" enctype="multipart/form-data" onsubmit="return checkCoords();">
			<div class="container">
				<div class="col-md-6 image-editor">
					<input class="col-md-10" name="image" type="file" onchange="showImage(this);"/>
					<button type="submit" class="col-md-2" align="right">Upload</button>
					<img id="image" >
					<p></p>
					<div class="button-group">
						<button type="button" onclick="rotateLeft();"><img src="resources\glyphicons-undo.png" ></button>&nbsp;&nbsp;&nbsp;&nbsp;
						<button type="button" onclick="rotateRight();"><img src="resources\glyphicons-redo.png"></button>
					</div>
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<input type="hidden" id="d" name="d" />
				</div>
			</div>
		</form>
	</body>
</html>