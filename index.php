<?php 
 if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name       = $_FILES['image']['name'];  
    $temp_name  = $_FILES['image']['tmp_name']; 
    
    if(isset($name)){
        if(!empty($name)){      
            $location = 'uploads/';  
            //echo 'File uploaded successfully';    
            if(move_uploaded_file($temp_name, $location.$name)){
                
                $targ_w = $targ_h = 150;
				$jpeg_quality = 90;

				$src = $location.$name;
				//echo $src;
				$img_r = imagecreatefromjpeg($src);
				// Rotate
				//$img_r = imagerotate($img_r, -$_POST['d'], 0);
				//imagejpeg($img_r,$src,$jpeg_quality);
				$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

				imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],
				$targ_w,$targ_h,$_POST['w'],$_POST['h']);

				$dst_r = imagerotate($dst_r, -$_POST['d'], 0);

				header('Content-type: image/jpeg');
				//imagejpeg($dst_r,$src,$jpeg_quality);
				imagejpeg($dst_r,null,$jpeg_quality);
				//echo "Image cropped and saved!!";
				exit;
            }
        }       
    }  else {
        echo 'You should select a file to upload !!';
    }
}
else{
	//echo "Sorry, Request not post";
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
		<style type="text/css">
			.image-editor{
				margin: 30%;
				border: 1px solid grey;
				border-radius: 10px;
				padding: 40px;
				overflow: auto;
				text-align: center;
				background-color: white;
			}
			#image{
				top: 10px;
				position: relative;
			}

			body{
				background-color: cyan;
			}
			.button-group{
				position: relative;
				top:20px;
			}
			.jcrop-holder{
				top:30px;
				margin: auto;
				background: white;
			}
			button{
				padding: 0px;
			}
		</style>

		<link rel="stylesheet" href="libs/JCrop/css/jquery.Jcrop.css" type="text/css" />
		<script type="text/javascript">
			function checkCoords(){
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};

			var jcrop_api;
			var angle=0;
			function rotateLeft(){
				angle -=90;
				console.log("rotateLeft");
				$(".jcrop-holder").rotate(angle);
				jcrop_api.setOptions({rotate : angle}); 
				if(angle<=-360)
					angle=0;
			}

			function rotateRight(){
				angle +=90;
				console.log("rotateRight");				
				$(".jcrop-holder").rotate(angle);
				jcrop_api.setOptions({rotate : angle}); 
				if(angle>=360) 
					angle=0;
			}			

			function updateCoords(c){
				$('#x').val(c.x) //*wRatio);
				$('#y').val(c.y) //*hRatio);
				$('#w').val(c.w) //*wRatio);
				$('#h').val(c.h) //*hRatio);
				$('#d').val(angle);
				console.log(angle);
			};

			var hRatio = 0;
	      	var wRatio = 0;
			function showImage(input){
				if(input.files && input.files[0]){
					var reader = new FileReader();
					reader.onload = function(e){
						$("#image")
							.attr("src",e.target.result)
							//.width(400)
							//.height(200)
							.Jcrop({
								onChange:   updateCoords,
								onSelect:   updateCoords,
								boxWidth: 450, boxHeight: 400
							},function(){
							      jcrop_api = this;
							    }); 

						$(".jcrop-holder").css("background-color","white");
				        $(".button-group").css("margin-top","20px");
					};
					reader.readAsDataURL(input.files[0]);
				}
			}
			

			function getImgSize(imgSrc) {
				if(wRatio==0 && hRatio ==0){
					hRatio = imgSrc.height/200;
					wRatio =imgSrc.width/400;

				//alert ('The image size is '+width+'*'+height);
				}		      

		    }

			
		</script>
	</head>
	<body>
		<form action="index.php" method="post" enctype="multipart/form-data" onsubmit="return checkCoords();">
			<div class="container">
				<div class="col-md-6 image-editor">
					<input class="col-md-10" name="image" type="file" onchange="showImage(this);"/>
					<button type="submit" class="col-md-2" align="right">Upload</button>
					<img id="image" src="" onload="getImgSize(this)" >
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