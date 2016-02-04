    /**
    * Note that in this app, we will rotate the jcrop-holder div and not just the image
    * jcrop-holder div is induced by JCrop library when jcrop function is called on the image
    */
    
    // Need to save the called JCrop function to reset the rotation variable 
    var jcrop_api;
    
    // Store the angle of rotation
    var angle=0;
    
    /**
    * Check before sunmitting whether all the co-ordinated including X,Y, height(h) and width(w).
    * All co-ordinated are required for cropping.
    * Alert the user otherwise.
    */
    function checkCoords(){
      if (parseInt($('#w').val())) return true;
      alert('Please select a crop region then press submit.');
      return false;
    };
    
    /**
    * rotateLeft function is used to rotate to left.
    * This rotates the whole div in stead of just image.
    * 
    */
    function rotateLeft(){

      // Subtract 90 from current angle to rotate by 90 in anti-clockwise direction.
      angle -=90;

      // Rotate the jcrop-holder div by angle value.
      $(".jcrop-holder").rotate(angle);
      
      // Bedore setting rotate variable, make it positive angle if it is negative
      // This is required to make sure that the left rotation does not cause the selector box 
      // to move in odd direction.
      jcrop_api.setOptions({rotate : angle<0? 360+angle:angle }); 
      
      // Wrap around the angle to 0 because angle can be max 360 or min 0 for rotation.
      if(angle<=-360)
        angle=0;
    }

    /**
    * rotateRight function is used to rotate to right.
    * This rotates the whole div in stead of just image.
    * 
    */
    function rotateRight(){

      // Subtract 90 from current angle to rotate by 90 in anti-clockwise direction.
      angle +=90;

      // Rotate the jcrop-holder div by angle value
      $(".jcrop-holder").rotate(angle);
      jcrop_api.setOptions({rotate : angle}); 
      
      // Wrap around the angle to 0 because angle can be max 360 or min 0 for rotation.
      if(angle>=360) 
        angle=0;
    }			

    /**
    * updateCoords function is called whenever the new area is selected
    * or the selector box is dragged around the image.
    */
    function updateCoords(c){
      // Update the co-ordinates after every mouse drag or select event.
      $('#x').val(c.x) 
      $('#y').val(c.y) 
      $('#w').val(c.w) 
      $('#h').val(c.h) 
      $('#d').val(angle);
    };

    /**
    * showImage function is invoked whenever the file control 
    * has selected new image file. This function reads the file and
    * loads it in the image tag.
    */
    function showImage(input){
      if(input.files && input.files[0]){
        
        // Read the image to load it in 
        var reader = new FileReader();

        // Call the onload function when the reader starts reading in the next line.
        reader.onload = function(e){
          $("#image")
            .attr("src",e.target.result)
            .Jcrop({
              // updateCoords will be called whenever the selector box is altered or moved(dragged).
              onChange:   updateCoords,
              onSelect:   updateCoords,

              // Set the width for the image which in turn will set the scale for the image automatically.
              boxWidth: 450, boxHeight: 400
            },function(){

                // Save the current jcrop instance for setting rotate variable in above functions.
                jcrop_api = this;
              }); 

        };

        // Load the image to the image tag.
        reader.readAsDataURL(input.files[0]);
      }
    }