<?php

if(isset($_POST['json']))
{
    $file = "maps/{$_GET['m']}.json";
    file_put_contents($file, $_POST['json']);
}

$mapx = 20;
$mapy = 20;

$boxx = 5;
$boxy = 5;
?>


<script src="jquery.js" type="text/javascript"></script>
<style>
.block
{
    display:inline-block;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
    box-sizing: border-box;
    width:99px;
    height:99px;
    border:0px solid #000;
    margin:0;
    padding:0;
    position:relative;
}
    
.block:hover
{
    background:#EEE;
    opacity:0.5;
}
    
.top
{
    border-top-width:1px;
}
    
.right
{
    border-right-width:1px;
}
    
.left
{
    border-left-width:1px;
}
    
.bottom
{
    border-bottom-width:1px;
}
    
.hover{
    position:absolute;
    top:-9999;
    left:-9999;
    background:#d3d3d3;
    height:33px;
    width:33px;
}
    
.boxes{
    position:fixed;
    left:0;
    top:0;
    right:0;
    bottom:25%;
    overflow:auto;
    border-bottom:1px solid blue;
    background:#fff;
    white-space:nowrap;
}
    
#export{
    position:fixed;
    left:0;
    bottom:0;
    width:40%;
    height:25%;
    padding:0;
    margin:0;
}
    
#import{
    position:fixed;
    right:0;
    bottom:0;
    width:40%;
    height:25%;
    padding:0;
    margin:0;
}
    
#submit{
    position:fixed;
    right:40%;
    bottom:0;
    width:20%;
    height:25%;
    padding:0;
    margin:0;
    line-height:100%;
}
    
#hiddenForm
{
    display:none;
}
</style>

<form method="post" id="hiddenForm" action="map.php?m=<?=(isset($_GET['m'])) ? $_GET['m'] : 'temp'?>">
    <input type="hidden" name="json" id="json" />
</form>

<div class="boxes"><div class="hover"></div>
<div class="hover"></div>
<div class="hover"></div>
<div class="hover"></div><?php

for($y = 0; $y < $mapy; $y++)
{
    for($x = 0; $x < $mapx; $x++)
    {
        $class = "block";
        if($x == 0 && $y < $boxy) $class .= " left";
        if($y == 0 && $x < $boxx) $class .= " top";
        if($x == $boxx && $y < $boxy) $class .= " left";
        if($y == $boxy && $x < $boxx) $class .= " top";
        echo "<div class='{$class}'></div>"; 
    }
    echo "<br>";
}

?></div>
<textarea id="export"></textarea>
<button id="submit">Save</button>
<textarea id="import" ></textarea>

<script>
function init()
{
$(".block").mousemove(function(e) {
    var pos = $(this).offset();
    pos.top += $(".boxes").scrollTop();
    pos.left += $(".boxes").scrollLeft();
    
    var x = Math.floor((e.pageX - $(this).offset().left) / 33);
    var y = Math.floor((e.pageY - $(this).offset().top) / 33);
    
    $(".hover").css("background", "#d3d3d3");
    
    $(".hover").index()
    
    $(".hover").eq(0).css("top", pos.top);
    $(".hover").eq(0).css("left", pos.left + 33);
    
    $(".hover").eq(1).css("top", pos.top + 33);
    $(".hover").eq(1).css("left", pos.left);
    
    $(".hover").eq(2).css("top", pos.top + 33);
    $(".hover").eq(2).css("left", pos.left + 66);
    
    $(".hover").eq(3).css("top", pos.top + 66);
    $(".hover").eq(3).css("left", pos.left + 33);
    
    
    
    var current = $(".block").index(this);
    
    var child = current + 1;
    
    if($('.block').eq(child).length != 0 && $('.block').eq(child).hasClass("left")) $(".hover").eq(2).css("background", "#9a464c"); 

    var child = current + <?=$mapx?>;
    if($('.block').eq(child).length != 0 && $('.block').eq(child).hasClass("top")) $(".hover").eq(3).css("background", "#9a464c");
    
    
    if($(this).hasClass("top")) $(".hover").eq(0).css("background", "#9a464c");
    if($(this).hasClass("left")) $(".hover").eq(1).css("background", "#9a464c");
    if($(this).hasClass("right")) $(".hover").eq(2).css("background", "#9a464c");
    if($(this).hasClass("bottom")) $(".hover").eq(3).css("background", "#9a464c");
    
    hover = false;
    
    if(x == 1 && y == 0) hover = 1;
    else if(x == 0 && y == 1) hover = 2;
    else if(x == 2 && y == 1) hover = 3;
    else if(x == 1 && y == 2) hover = 4;
    
    if(hover) $(".hover:nth-child(" + hover + ")").css("background", "#469a5c");
    
}).mouseout(function() {
    $(".hover").css("top", -9999);
    $(".hover").css("left", -9999);
});
    
$(".block").click(function(e) {
    var pos = $(this).offset();
    pos.top += $(".boxes").scrollTop();
    pos.left += $(".boxes").scrollLeft();
    
    var x = Math.floor((e.pageX - $(this).offset().left) / 33);
    var y = Math.floor((e.pageY - $(this).offset().top) / 33);
    
    var current = $(".block").index(this);
    
    
    if(x == 1 && y == 0) $(this).toggleClass('top');
    else if(x == 0 && y == 1) $(this).toggleClass('left');
    else if(x == 2 && y == 1)
    {
        var child = current + 1;
        if($('.block').eq(child).length != 0) $('.block').eq(child).toggleClass('left');
        else $(this).toggleClass('right');   
    }
    else if(x == 1 && y == 2) 
    {
        var child = current + <?=$mapx?>;
        if($('.block').eq(child).length != 0) $('.block').eq(child).toggleClass('top');
        else $(this).toggleClass('bottom');   
    }
    
    
    
    
    
    /*var horiz = [];
    
    var count = 0;
    var start = -1;
    $( ".block" ).each(function() {
        var y = Math.floor(count / <?=$mapx?>);
        var x = count - y * <?=$mapx?>;
        if(x == 0) start = -1;
        if($(this).hasClass("top"))
        {
            if(start == -1) start = x;
            found = false;
            
            for(var z = 0; z < horiz.length; z++)
            {
                h = horiz[z];
                if(h[0] == start && h[1] == y)
                {
                    h[2]++;
                    found = true;
                }
            }
            if(!found) horiz.push([start, y, 1]);
        }
        count++;
    });
    
    count = 0;
    start = -1;
    $( ".block" ).each(function() {
        var y = Math.floor(count / <?=$mapx?>);
        var x = count - y * <?=$mapx?>;
        y++;
        if(x == 0) start = -1;
        if($(this).hasClass("bottom"))
        {
            if(start == -1) start = x;
            found = false;
            
            for(var z = 0; z < horiz.length; z++)
            {
                h = horiz[z];
                if(h[0] == start && h[1] == y)
                {
                    h[2]++;
                    found = true;
                }
            }
            if(!found) horiz.push([start, y, 1]);
        }
        count++;
    });*/
    
    
    
    
    
    var horiz = [];
    
    var count = 0;
    var start = -1;
    for(var y = 0; y < <?=$mapy?>; y++)
    {
        start = -1;
        for(var x = 0; x < <?=$mapx?>; x++)
        {
            var block = y * (<?=$mapx?>) + x;
            if($(".block").eq(block).hasClass("top"))
            {
                if(start == -1) start = x;
                found = false;

                for(var z = 0; z < horiz.length; z++)
                {
                    h = horiz[z];
                    if(h[0] == start && h[1] == y)
                    {
                        h[2]++;
                        found = true;
                    }
                }
                if(!found) horiz.push([start, y, 1]); 
            }
            else start = -1;
        }
    }
    
    var vert = [];
    
    count = 0;
    start = -1;
    for(var x = 0; x < <?=$mapx?>; x++)
    {
        start = -1;
        for(var y = 0; y < <?=$mapy?>; y++)
        {
            var block = y * (<?=$mapx?>) + x;
            if($(".block").eq(block).hasClass("left"))
            {
                if(start == -1) start = y;
                found = false;

                for(var z = 0; z < vert.length; z++)
                {
                    v = vert[z];
                    if(v[1] == start && v[0] == x)
                    {
                        v[2]++;
                        found = true;
                    }
                }
                if(!found) vert.push([x, start, 1]); 
            }
            else start = -1;
        }
    }
    
    /*count = 0;
    start = -1;
    for(var x = 0; x < <?=$mapx?>; x++)
    {
        start = -1;
        for(var y = 0; y < <?=$mapy?>; y++)
        {
            var block = y * (<?=$mapx?> + 1) + x + 1 + $(".block").index();
            if($(".block:nth-child(" + block + ")").hasClass("right"))
            {
                if(start == -1) start = y;
                found = false;

                for(var z = 0; z < vert.length; z++)
                {
                    v = vert[z];
                    if(v[1] == start && v[0] == x + 1)
                    {
                        v[2]++;
                        found = true;
                    }
                }
                if(!found) vert.push([x + 1, start, 1]); 
            }
        }
    }
    
    text = "exports.horiz = [ ";
    for(var z = 0; z < horiz.length; z++)
    {
        h = horiz[z];
        if(z != 0) text += ", ";
        text += "[" + h[0] + ", " + h[1] + ", " + h[2] + " ]";
    }
    text += " ];\n";
    text += "exports.vert = [ ";
    for(var z = 0; z < vert.length; z++)
    {
        v = vert[z];
        if(z != 0) text += ", ";
        text += "[" + v[0] + ", " + v[1] + ", " + v[2] + " ]";
    }
    text += " ];\n\n";
    text += "var mapper = '" + $('.boxes').html() + "'; var x = <?=$mapx?>; var y = <?=$mapy?>;";*/
    
    var text = {"vert": vert, "horiz": horiz}
    text = JSON.stringify(text);
    $('#export').html(text);
});
    
}
init();

$('#import').change(function() {
    importmap($(this).val());
});
    
<?php 
if($_GET['m'])
{
    $file = "maps/{$_GET['m']}.json";
    $text = file_get_contents($file);
    
    echo "importmap('{$text}');";
}
?>
    
function importmap(text)
{
    $('.block').attr('class', 'block');
    text = JSON.parse(text);
    
    for(var x = 0; x < text.vert.length; x++)
    {
        v = text.vert[x];
        for(var y = 0; y < v[2]; y++)
        {
            var child = (v[1] + y) * (<?=$mapx?>) + v[0];
            $('.block').eq(child).toggleClass('left');
        }
    }
    
    for(var x = 0; x < text.horiz.length; x++)
    {
        h = text.horiz[x];
        for(var y = 0; y < h[2]; y++)
        {
            var child = (h[1]) * (<?=$mapx?>) + h[0] + y;
            $('.block').eq(child).toggleClass('top');
        }
    }
}

$('#submit').click(function() {
    $("#json").val($("#export").val());
    $("#hiddenForm").submit();
});

</script>