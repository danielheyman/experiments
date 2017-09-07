<script src="jquery.js" type="text/javascript"></script>
<!--<script src="http://ricostacruz.com/jquery.transit/jquery.transit.min.js"></script>-->
<script src="http://67.207.155.35:8080/socket.io/socket.io.js"></script>
<script>
    $(document).ready(function(){
        var socket = io.connect('http://67.207.155.35:8080');
        
        var keys = {};
        
        $(document).keydown(function (e) {
            keys[e.which] = true;
        });

        $(document).keyup(function (e) {
            delete keys[e.which];
            if(e.which == 32) //fire
            {
               socket.emit('fire', 'bullet');
            }
        });
        
        setInterval(function() {
            for(i in keys)
            {
                if(keys.hasOwnProperty(i))
                {
                    if(i == 37) //left
                    {
                       socket.emit('move', 'left');
                    }
                    else if(i == 38) //up
                    {
                       socket.emit('move', 'up');  
                    }
                    else if(i == 39) //right
                    {
                       socket.emit('move', 'right'); 
                    }
                    else if(i == 40) //down
                    {
                       socket.emit('move', 'down');
                    }
                }
            }
        }, 35);
        
        socket.on('setup', function (data) {
            /*$.fx.speeds._default = 20;
            
            
            var scalex = window.innerWidth / data.width;
            var scaley = (window.innerHeight - 40) / data.height;
            var scale = (scalex < scaley) ? scalex : scaley;
            
            if($("#map").css('height').replace("px", "") != data.height)
            {
                console.log('hi');
                var map = {
                    height: data.height,
                    width: data.width,
                    transformOrigin: '0px 0px',
                    scale: scale,
                    left: ((window.innerWidth - data.width * scale) / 2),
                    top: (((window.innerHeight - 40) - data.height * scale) / 2)
                };


                $("#map").css(map);
                
                for(var x = 0; x < data.vert.length; x++)
                {
                    v = data.vert[x];
                    $("#walls")
                    $("#walls").append("<div style='left: '");
                    context.moveTo(v[0], v[1]);
                    context.lineTo(v[0], v[1] + v[2]);
                    context.stroke();
                }
            }
            
            
            for(var x = 0; x < data.points.length; x++)
            {
                var point = data.points[x];
                if($("#t" + data.id[x]).length == 0)
                {
                    $("#tanks").append('<div class="tank" id="t' + data.id[x]  + '" style="position"><div class="sides"></div></div>');
                    var setup = {
                        width: point[2],
                        height: point[3],
                        background: data.colors[x]
                    };
                    $("#t" + data.id[x]).css(setup);
                    $("#t" + data.id[x] + " .sides").css("border-color", data.colors2[x]);  
                }
                var css = {
                    rotate: point[4] + 'deg',
                    left: point[0],
                    top: point[1]
                };
                console.log(css);
                $("#t" + data.id[x]).transition(css);
            }
            */
            
            var scalex = window.innerWidth / data.width;
            var scaley = (window.innerHeight - 40) / data.height;
            if(scalex < scaley) scaley = scalex;
            else if(scalex > scaley) scalex = scaley;
            
            canvas.width = data.width * scalex;
            canvas.height = data.height * scaley;
            
            context.setTransform(1, 0, 0, 1, 0, 0);
            
            context.scale(canvas.width / data.width, canvas.height / data.height);
            $("#canvas").css("position", "absolute");
            $("#canvas").css("left", ((window.innerWidth - canvas.width) / 2));
            $("#canvas").css("top", (((window.innerHeight - 40) - canvas.height) / 2));
            
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.lineWidth = 0.5;
            
            context.strokeStyle = '#000';
            for(var x = 0; x < data.vert.length; x++)
            {
                v = data.vert[x];
                context.beginPath();
                context.moveTo(v[0], v[1]);
                context.lineTo(v[0], v[1] + v[2]);
                context.stroke();
            }
            for(var x = 0; x < data.horiz.length; x++)
            {
                v = data.horiz[x];
                context.beginPath();
                context.moveTo(v[0], v[1]);
                context.lineTo(v[0] + v[2], v[1]);
                context.stroke();
            }
            for(var x = 0; x < data.bullets.length; x++)
            {
                b = data.bullets[x];
                
                
                context.rect(b[0], b[1], 0.5, 0.5);
                context.fill();
            }
            
            $("#score").html("");
            for(var x = 0; x < data.points.length; x++)
            {
                points = data.points[x];
                context.beginPath();
                context.lineWidth = 0.4;
                context.moveTo(points['topleft'][0], points['topleft'][1]);
                context.lineTo(points['topright'][0], points['topright'][1]);
                context.lineTo(points['bottomright'][0], points['bottomright'][1]);
                context.lineTo(points['bottomleft'][0], points['bottomleft'][1]);
                context.fillStyle = data.colors[x];
                context.strokeStyle = data.colors2[x];
                context.closePath();
                context.fill();
                context.stroke();
                
                
                points = data.points[x];
                context.beginPath();
                context.lineWidth = 0.4;
                context.moveTo(points['topleft'][0], points['topleft'][1]);
                context.lineTo(points['topright'][0], points['topright'][1]);
                context.strokeStyle = "#000";
                context.closePath();
                context.fill();
                context.stroke();
                
                
                points = data.points[x];
                context.beginPath();
                context.lineWidth = 0.3;
                context.moveTo(points['topleft'][0], points['topleft'][1]);
                context.lineTo(points['topright'][0], points['topright'][1]);
                context.strokeStyle = "#fff";
                context.closePath();
                context.fill();
                context.stroke();
                
                if(x != 0) $("#score").append(" &nbsp; ");
                $("#score").append('<div class="block" style="border-color:' + data.colors2[x] + '; background:' + data.colors[x] + ';"></div> scored ' + data.scores[x]);
            }
        });
    });
</script>

<!--<style>
body{
    padding:0;
    margin:0;
    
}
    
#map{
    /*border:1px solid black;*/
    position:absolute;
}
    
#walls div{
    border:1px solid #000;
    position:absolute;
}

#tanks .tank{
    position:absolute;
    width:5px;
    height:6px;
    display:inline-block;
    position:absolute;
}
    
#tanks .tank .sides{
    width:100%;
    height:100%;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;  
    border:0.5px solid gray;
}

</style>

<div id="map">
    <div class="walls"></div>
    <div id="tanks"></div>
</div>-->

<!DOCTYPE HTML>
<html>
  <head>
    <style>
      body {
        margin: 0px;
        padding: 0px;
      }
    </style>
  </head>
  <body>
    <canvas id="canvas" width="700" height="700"></canvas>
      <style>.block{width:18px; height:18px; display:inline-block; border:2px solid #fff; position:relative; top:5;}</style>
      <div id="score" style="position:fixed; height:40px; bottom:0; right:0; left:0; text-align:center; line-height:30px; font-size:20px;">Score</div>
    <script>
        var canvas = document.getElementById('canvas');
        var context = canvas.getContext('2d');
        
    </script>
  </body>
</html>