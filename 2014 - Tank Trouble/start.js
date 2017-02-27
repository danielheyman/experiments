var border = 0.45;
var borderBullet = 0.6;
var myBorder = 0.2;
var box = 10;
var rotation = 9;
var bulletTime = 500;
var maps = ['map', 'map2', 'map3', 'map4', 'temp'];
var waitTime = 50;


var fs = require('fs');
var io = require('socket.io').listen(8080);


var height = 0;
var width = 0;
var map;
var obj;
var vert;
var horiz;
var change;
var temptime = 0;

function resetMap()
{
    height = 0;
    width = 0;
    map = maps[Math.floor(Math.random()*maps.length)];
    obj = JSON.parse(fs.readFileSync('maps/' + map + '.json', 'utf8'));
    vert = obj.vert;
    horiz = obj.horiz;
    
    
    for(var x = 0; x < vert.length; x++)
    {
        for(var y = 0; y < 3; y++)
        {
            vert[x][y] *= box;
        }
        if(vert[x][0] > width) width = vert[x][0];
        if(vert[x][1] + vert[x][2] > height) height = vert[x][1] + vert[x][2];
    }


    for(var x = 0; x < horiz.length; x++)
    {
        for(var y = 0; y < 3; y++)
        {
            horiz[x][y] *= box;
        }
        if(horiz[x][0] + horiz[x][2] > width) width = horiz[x][0] + horiz[x][2];
        if(horiz[x][1] > height) height = horiz[x][1];
    }
    
    var tempx = [];
    var tempy = [];
    for (var socketId in io.sockets.sockets) {
        io.sockets.sockets[socketId].get('me', function(err, me) {
            
            do
            {
                me[0] = Math.floor((Math.random()*(width / box))) * box + box / 3;
            }
            while(tempx.contains(me[0]));
            
            do 
            {
                me[1] = Math.floor((Math.random()*(height / box))) * box + box / 3;
            }
            while(tempy.contains(me[1]));
            
            me[4] = Math.floor((Math.random()*(360 / rotation))) * rotation;
            
            tempx.push(me[0]);
            tempy.push(me[1]);

            io.sockets.sockets[socketId].set('me', me);
            io.sockets.sockets[socketId].set('bullet', false);
        });
    }
    change = true;
}
resetMap();


Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] == needle) return true;
   }
   return false;
}


function RotatePoints(x, y, width2, height2, deg)
{
    sin = Math.sin(deg / 180 * Math.PI);
    cos = Math.cos(deg / 180 * Math.PI);
    // translate point back to origin:
    x -= width2;
    y -= height2;

    // rotate point
    temp_x = x * cos - y * sin;
    temp_y = x * sin + y * cos;

    // translate point back:
    temp_x += width2;
    temp_y += height2;

    return [temp_x, temp_y];
}

function getPoints(me)
{
    return {"topleft": RotatePoints(me[0], me[1], me[0] + me[2] / 2, me[1] + me[3] / 2, me[4]),
           "topright": RotatePoints(me[0] + me[2], me[1], me[0] + me[2] / 2, me[1] + me[3] / 2, me[4]),
           "bottomleft": RotatePoints(me[0], me[1] + me[3], me[0] + me[2] / 2, me[1] + me[3] / 2, me[4]),
           "bottomright": RotatePoints(me[0] + me[2], me[1] + me[3], me[0] + me[2] / 2, me[1] + me[3] / 2, me[4])};
}

function setup(me)
{
    var points = getPoints(me);
    
    collision = false;
    for(var y = 0; y < vert.length; y++)
    {
        if(collision) break;
        var v = vert[y];
        for(var x = 0; x < 4; x++)
        {
            if(x == 0)
            {
                point1 = points['topleft'];
                point2 = points['topright'];
            }
            else if(x == 1)
            {
                point1 = points['topright'];
                point2 = points['bottomright'];
            }
            else if(x == 2)
            {
                point1 = points['bottomright'];
                point2 = points['bottomleft'];
            }
            else if(x == 3)
            {
                point1 = points['bottomleft'];
                point2 = points['topleft'];
            }

            slope = ((point2[1] - point1[1]) / (point2[0] - point1[0]));
            b = point1[1] - point1[0] * slope;

            location = v[0] + border;

            if((point1[0] <= location && location <= point2[0]) || (point2[0] <= location && location <= point1[0]))   
            {
                ypoint = slope * location + b;
                if(v[1] <= ypoint && ypoint <= v[1] + v[2])
                {
                    collision = true;
                    break;
                }
            }

            location = v[0] - border;

            if((point1[0] <= location && location <= point2[0]) || (point2[0] <= location && location <= point1[0]))   
            {
                ypoint = slope * location + b;
                if(v[1] <= ypoint && ypoint <= v[1] + v[2])
                {
                    collision = true;
                    break;
                }
            }
        }
    }

    for(var y = 0; y < horiz.length; y++)
    {
        if(collision) break;
        var h = horiz[y];
        for(x = 0; x < 4; x++)
        {
            if(x == 0)
            {
                point1 = points['topleft'];
                point2 = points['topright'];
            }
            else if(x == 1)
            {
                point1 = points['topright'];
                point2 = points['bottomright'];
            }
            else if(x == 2)
            {
                point1 = points['bottomright'];
                point2 = points['bottomleft'];
            }
            else if(x == 3)
            {
                point1 = points['bottomleft'];
                point2 = points['topleft'];
            }

            slope = (point2[0] - point1[0] != 0) ? ((point2[1] - point1[1]) / (point2[0] - point1[0])) : 123456789;
            b = point1[1] - point1[0] * slope;


            location = h[1] + border;

            if((point1[1] <= location && location <= point2[1]) || (point2[1] <= location && location <= point1[1]))   
            {
                xpoint = (slope == 123456789) ? point1[0] : (location - b) / slope;
                if(h[0] <= xpoint && xpoint <= h[0] + h[2])
                {
                    collision = true;
                    break;
                }
            }


            location = h[1] - border;

            if((point1[1] <= location && location <= point2[1]) || (point2[1] <= location && location <= point1[1]))   
            {
                xpoint = (slope == 123456789) ? point1[0] : (location - b) / slope;
                if(h[0] <= xpoint && xpoint <= h[0] + h[2])
                {
                    collision = true;
                    break;
                }
            }
        }
    }

    return (collision) ? false : true;
}

io.sockets.on('connection', function (socket) {
    
    var me = [0, 0, 3, 4, 0];
    
    me[0] = Math.floor((Math.random()*(width / box))) * box + box / 3;
    me[1] = Math.floor((Math.random()*(height / box))) * box + box / 3;
    me[4] = Math.floor((Math.random()*(360 / rotation))) * rotation;
    
    socket.set('me', me);
    socket.set('bullet', false);
    socket.set('score', 0);
    socket.set('color', '#'+Math.floor(Math.random()*16777215).toString(16));
    socket.set('color2', '#'+Math.floor(Math.random()*16777215).toString(16));
    change = true;
    
    socket.on('fire', function (data) {
        var me;
        socket.get('me', function(err, data) {
            me = data.slice();
        });
        
        if(me[0] != -9999 && me[1] != -9999)
        {
            if(data == 'bullet')
            {
                socket.get('bullet', function(err, bullet) {
                    if(!bullet)
                    {
                        console.log('hi');
                        var points = getPoints(me);
                        var x = (points["topleft"][0] + points["topright"][0]) / 2;
                        var y = (points["topleft"][1] + points["topright"][1]) / 2;
                        socket.set('bullet', [x, y, me[4], bulletTime]);
                        change = true;
                    }
                });
            }
        }
    });
    socket.on('move', function (data) {
        var me;
        var mebackup;
        socket.get('me', function(err, data) {
            me = data.slice();
            mebackup = data.slice();
        });
        
        if(me[0] != -9999 && me[1] != -9999)
        {
            var old = me.slice();
            var failed = false;
            if(data == 'left')
            {
                me[4] -= rotation;    
                if(me[4] < 0) me[4] += 360;
                if(!setup(me)) failed = true;
            }
            else if(data == 'up')
            {
                var negx = 1;
                var negy = 1;
                if(me[4] >= 0 && me[4] <= 90)
                {
                    negx = 1;
                    negy = -1;
                }
                else if(me[4] > 90 && me[4] <= 180)
                {
                    negx = 1;
                    negy = 1;
                }
                else if(me[4] > 180 && me[4] <= 270)
                {
                    negx = -1;
                    negy = 1;
                }
                else if(me[4] > 270 && me[4] < 360)
                {
                    negx = -1;
                    negy = -1;
                }


                var found = false;
                for(var x = 0.7; x > 0; x = x - 0.1)
                {
                    me[0] = mebackup[0] + x * negx * Math.abs(Math.sin(me[4] / 180 * Math.PI));  
                    me[1] = mebackup[1] + x * negy * Math.abs(Math.cos(me[4] / 180 * Math.PI));
                    if(setup(me))
                    {
                        found = true;
                        break;
                    }
                }
                if(!found) failed = true;
            }
            else if(data == 'right')
            {
                me[4] += rotation;
                if(me[4] >= 360) me[4] -= 360;
                if(!setup(me)) failed = true;
            }
            else if(data == 'down')
            {
                var negx = 1;
                var negy = 1;
                if(me[4] >= 0 && me[4] <= 90)
                {
                    negx = 1;
                    negy = -1;
                }
                else if(me[4] > 90 && me[4] <= 180)
                {
                    negx = 1;
                    negy = 1;
                }
                else if(me[4] > 180 && me[4] <= 270)
                {
                    negx = -1;
                    negy = 1;
                }
                else if(me[4] > 270 && me[4] < 360)
                {
                    negx = -1;
                    negy = -1;
                }


                var found = false;
                for(var x = 0.5; x > 0; x = x - 0.1)
                {
                    me[0] = mebackup[0] + x * -1 * negx * Math.abs(Math.sin(me[4] / 180 * Math.PI));  
                    me[1] = mebackup[1] + x * -1 * negy * Math.abs(Math.cos(me[4] / 180 * Math.PI));
                    if(setup(me))
                    {
                        found = true;
                        break;
                    }
                }
                if(!found) failed = true;
            }
            if(!failed)
            {
                socket.set('me', me);
                change = true;
            }
        }
    });
});
    
setInterval(function() {
    for (var socketId in io.sockets.sockets) {
        io.sockets.sockets[socketId].get('bullet', function(err, bullet) {
            if(bullet != false)
            {
                
                change = true;
                if(bullet[3] < bulletTime - 5)
                {
                    for (var socketId2 in io.sockets.sockets) {
                        if(bullet != false)
                        {
                            io.sockets.sockets[socketId2].get('me', function(err, me) {
                                var newpoint = RotatePoints(bullet[0], bullet[1], me[0] + me[2] / 2, me[1] + me[3] / 2, me[4]);
                                if(newpoint[0] >= me[0] - myBorder && newpoint[0] <= me[0] + me[2] + myBorder)
                                {
                                    if(newpoint[1] >= me[1] - myBorder && newpoint[1] <= me[1] + me[3] + myBorder)
                                    {
                                        me[0] = -9999;
                                        me[1] = -9999;
                                        bullet = false;
                                        io.sockets.sockets[socketId2].set('me', me);
                                        temptime = waitTime;
                                    }
                                }
                            });
                        }
                    }
                }
                
                if(bullet != false)
                {
                    var negx = 1;
                    var negy = 1;
                    if(bullet[2] >= 0 && bullet[2] <= 90)
                    {
                        negx = 1;
                        negy = -1;
                    }
                    else if(bullet[2] > 90 && bullet[2] <= 180)
                    {
                        negx = 1;
                        negy = 1;
                    }
                    else if(bullet[2] > 180 && bullet[2] <= 270)
                    {
                        negx = -1;
                        negy = 1;
                    }
                    else if(bullet[2] > 270 && bullet[2] < 360)
                    {
                        negx = -1;
                        negy = -1;
                    }
                    bullet[3]--;
                    bullet[0] += 0.7 * negx * Math.abs(Math.sin(bullet[2] / 180 * Math.PI));  
                    bullet[1] += 0.7 * negy * Math.abs(Math.cos(bullet[2] / 180 * Math.PI));

                    for(var y = 0; y < vert.length; y++)
                    {
                        var v = vert[y];
                        if(v[0] + borderBullet >= bullet[0] && v[0] - borderBullet <= bullet[0])
                        {
                            if(v[1] - borderBullet <= bullet[1] && v[1] + v[2] + borderBullet >= bullet[1])
                            {
                                bullet[0] += 0.7 * negx * Math.abs(Math.sin(bullet[2] / 180 * Math.PI));  
                                bullet[1] += 0.7 * negy * Math.abs(Math.cos(bullet[2] / 180 * Math.PI));

                                bullet[2] = 360 - bullet[2];

                                bullet[0] -= 0.7 * negx * Math.abs(Math.sin(bullet[2] / 180 * Math.PI));  
                                bullet[1] -= 0.7 * negy * Math.abs(Math.cos(bullet[2] / 180 * Math.PI));
                                break;
                            }
                        }
                    }

                    for(var y = 0; y < horiz.length; y++)
                    {
                        var h = horiz[y];
                        if(h[1] + borderBullet >= bullet[1] && h[1] - borderBullet <= bullet[1])
                        {
                            if(h[0] - borderBullet <= bullet[0] && h[0] + h[2] + borderBullet >= bullet[0])
                            {
                                bullet[0] += 0.7 * negx * Math.abs(Math.sin(bullet[2] / 180 * Math.PI));  
                                bullet[1] += 0.7 * negy * Math.abs(Math.cos(bullet[2] / 180 * Math.PI));

                                if(bullet[2] <= 90) bullet[2] = 180 - bullet[2];
                                else if(bullet[2] <= 180) bullet[2] = 180 - bullet[2];
                                else if(bullet[2] <= 270) bullet[2] = 360 - (bullet[2] - 180);
                                else bullet[2] = 180 + (360 - bullet[2]);

                                bullet[0] -= 0.7 * negx * Math.abs(Math.sin(bullet[2] / 180 * Math.PI));  
                                bullet[1] -= 0.7 * negy * Math.abs(Math.cos(bullet[2] / 180 * Math.PI));
                                break;
                            }
                        }
                    }

                    if(bullet[3] <= 0) bullet = false;
                }
                io.sockets.sockets[socketId].set('bullet', bullet);
            }
        });
    }
}, 20);

setInterval(function() {
    
    if(change || temptime != 0)
    {
        change = false;
        var points = [];
        var colors = [];
        var colors2 = [];
        var scores = [];
        var bullets = [];
        var winner = 9999;
        var id = [];
        
        
        for (var socketId in io.sockets.sockets) {
            io.sockets.sockets[socketId].get('me', function(err, me) {
                points.push(getPoints(me));
                //points.push(me);
                id.push(socketId);
                if(me[0] != -9999 && me[1] != -9999)
                {
                    if(winner == 9999) winner = socketId;   
                    else winner = false;
                }
            });
        }
       
        
        if(!winner || points.length < 2 || temptime > 1)//if(!winner || points.length < 2 || temptime > 1)
        {
            for (var socketId in io.sockets.sockets) {
                io.sockets.sockets[socketId].get('bullet', function(err, bullet) {
                    if(bullet != false)
                    {
                        bullets.push([bullet[0], bullet[1]]);
                    }
                });
                
                io.sockets.sockets[socketId].get('score', function(err, score) {
                    scores.push(score);
                });
                
                io.sockets.sockets[socketId].get('color', function(err, color) {
                    colors.push(color);
                });
                
                io.sockets.sockets[socketId].get('color2', function(err, color2) {
                    colors2.push(color2);
                });
            }
            if(points.length != 0) io.sockets.emit('setup', { vert: vert, horiz: horiz, points: points, scores: scores, colors: colors, colors2: colors2, bullets: bullets, height: height, width: width, id: id});
        }
        else
        {
            if(winner != 9999)
            {
                io.sockets.sockets[winner].get('score', function(err, score) {
                    io.sockets.sockets[winner].set('score', score + 1);

                });
            }
            resetMap();   
        }
        
        if(temptime != 0) temptime--;
    }
}, 40);