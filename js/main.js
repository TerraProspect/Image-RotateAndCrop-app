    var jcrop_api;
    var angle=0;
    function checkCoords(){
      if (parseInt($('#w').val())) return true;
      alert('Please select a crop region then press submit.');
      return false;
    };
    
    function rotateLeft(){
      angle -=90;
      console.log("rotateLeft");
      $(".jcrop-holder").rotate(angle);
      jcrop_api.setOptions({rotate : angle<0? 360+angle:angle }); 
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
      $('#x').val(c.x) 
      $('#y').val(c.y) 
      $('#w').val(c.w) 
      $('#h').val(c.h) 
      $('#d').val(angle);
      console.log(angle);
    };

    function showImage(input){
      if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
          $("#image")
            .attr("src",e.target.result)
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